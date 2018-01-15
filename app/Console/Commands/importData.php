<?php

namespace App\Console\Commands;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Swapi;
use App\Models\Database\People;
use App\Models\Database\Planet;
use App\Models\Database\Film;
use App\Models\Database\Species;
use App\Models\Database\Starship;
use App\Models\Database\Vehicle;

class importData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data from API';

    protected $swapi;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Swapi $swapi)
    {
        parent::__construct();

        $this->swapi = $swapi;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (!$this->confirm('This command will reset SWAPI-related information in database. Do you wish to continue?')) {
            return;
        }

        Artisan::call('migrate:refresh', ['--step' => 1]);

        try {
            $this->processPlanets($this->fetchAll('planets/'));
            $this->processSpecies($this->fetchAll('species/'));
            $this->processVehicles($this->fetchAll('vehicles/'));
            $this->processStarships($this->fetchAll('starships/'));
            $this->processPeople($this->fetchAll('people/'));
            $this->processFilms($this->fetchAll('films/'));
        } catch (QueryException $exception) {
            $this->error('Error while inserting data:');
            $this->error($exception->getMessage());
            return;
        } catch (\Exception $exception) {
            $this->error($exception->getMessage());
            return;
        }


        $this->info('Information was successfully imported');
    }

    /**
     * @param $subURL
     * @return array
     * @throws \Exception When when fails to fetch data
     */
    private function fetchAll($subURL)
    {
        $page = 1;
        $data = [];

        do {
            $response = $this->swapi->get($this->swapi->getBaseURL() . $subURL . '?page=' . $page);

            if (!$response instanceof \stdClass || !property_exists($response, 'results') || !property_exists($response, 'next')) {
                throw new \Exception('Error while fetching data');
            }

            $data = array_merge($data, $response->results);
            $page++;

        } while (!is_null($response->next));

        return $data;
    }

    /**
     * Returns array of data which contains only allowed columns for specified model
     *
     * @param $arrayOfObjects
     * @param $modelClass
     * @return array
     */
    private function prepareData($arrayOfObjects, $modelClass)
    {
        $columns = Schema::getColumnListing((new $modelClass)->getTable());

        return array_map(function ($object) use ($columns) {
            $data = array_filter((array)$object, function ($field) use ($columns) {
                return in_array($field, $columns);
            }, ARRAY_FILTER_USE_KEY);

            array_walk($data, function (&$value, $column) {
                if ($value === 'unknown') {
                    $value = null;
                }

                if ($column === 'created' || $column === 'edited') {
                    $value = (new \DateTime($value))->format('Y-m-d H:i:s');
                }
            });

            return $data;
        }, $arrayOfObjects);
    }

    private function processFilms($data)
    {
        Film::insert($this->prepareData($data, Film::class));

        // here we begin something terrible
        foreach ($data as $film) {
            foreach ($film->characters as $characterURL) {
                DB::insert(
                    'INSERT INTO people_to_films (people_id, film_id) VALUES (?, ?)',
                    [
                        People::where('url', $characterURL)->first()->id,
                        Film::where('url', $film->url)->first()->id,
                    ]
                );
            }

            foreach ($film->vehicles as $vehicleURL) {
                DB::insert(
                    'INSERT INTO vehicles_to_films (vehicle_id, film_id) VALUES (?, ?)',
                    [
                        Vehicle::where('url', $vehicleURL)->first()->id,
                        Film::where('url', $film->url)->first()->id,
                    ]
                );
            }

            foreach ($film->starships as $starshipURL) {
                DB::insert(
                    'INSERT INTO starships_to_films (starship_id, film_id) VALUES (?, ?)',
                    [
                        Starship::where('url', $starshipURL)->first()->id,
                        Film::where('url', $film->url)->first()->id,
                    ]
                );
            }

            foreach ($film->planets as $planetURL) {
                DB::insert(
                    'INSERT INTO planets_to_films (planet_id, film_id) VALUES (?, ?)',
                    [
                        PLanet::where('url', $planetURL)->first()->id,
                        Film::where('url', $film->url)->first()->id,
                    ]
                );
            }
        }
    }

    private function processPlanets($data)
    {
        Planet::insert($this->prepareData($data, Planet::class));
    }

    private function processPeople($data)
    {
        $preparedData = $this->prepareData($data, People::class);

        foreach ($preparedData as &$person) {
            $person['mass'] = People::replaceDecimalDelimiter($person['mass']);

            if (!in_array($person['gender'], People::getPossibleEnumValues('gender'))) {
                $person['gender'] = null;
            }

            $planet = DB::table('planets')->select('id')->where('url', $person['homeworld'])->get()->first();
            $person['homeworld'] = !is_null($planet) ? $planet->id : null;
        }

        People::insert($preparedData);

        foreach ($data as $person) {
            foreach ($person->species as $speciesURL) {
                DB::insert(
                    'INSERT INTO people_to_species (people_id, species_id) VALUES (?, ?)',
                    [
                        People::where('url', $person->url)->first()->id,
                        Species::where('url', $speciesURL)->first()->id,
                    ]
                );
            }

            foreach ($person->vehicles as $vehicleURL) {
                DB::insert(
                    'INSERT INTO people_to_vehicles (people_id, vehicle_id) VALUES (?, ?)',
                    [
                        People::where('url', $person->url)->first()->id,
                        Vehicle::where('url', $vehicleURL)->first()->id,
                    ]
                );
            }

            foreach ($person->starships as $starshipURL) {
                DB::insert(
                    'INSERT INTO people_to_starships (people_id, starship_id) VALUES (?, ?)',
                    [
                        People::where('url', $person->url)->first()->id,
                        Starship::where('url', $starshipURL)->first()->id,
                    ]
                );
            }
        }
    }

    private function processSpecies($data)
    {
        $data = $this->prepareData($data, Species::class);

        foreach ($data as &$species) {
            if ((int)$species['average_height'] === 0) {
                $species['average_height'] = null;
            }

            if ((int)$species['average_lifespan'] === 0) {
                $species['average_lifespan'] = null;
            }
        }

        Species::insert($data);
    }

    private function processVehicles($data)
    {
        $data = $this->prepareData($data, Vehicle::class);

        foreach ($data as &$vehicle) {
            if ((int)$vehicle['cargo_capacity'] === 0) {
                $vehicle['cargo_capacity'] = null;
            }
        }

        Vehicle::insert($data);
    }

    private function processStarships($data)
    {
        $data = $this->prepareData($data, Starship::class);

        foreach ($data as &$starship) {
            if ((int)$starship['max_atmosphering_speed'] === 0) {
                $starship['max_atmosphering_speed'] = null;
            }
        }

        Starship::insert($data);
    }
}
