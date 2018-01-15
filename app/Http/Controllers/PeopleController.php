<?php

namespace App\Http\Controllers;

use App\Models\Database\Film;
use App\Models\Database\People;
use App\Models\Database\Planet;
use App\Models\Database\Species;
use App\Models\Database\Vehicle;
use App\Models\Database\Starship;
use App\Repositories\Contracts\IDatabaseSourceRepository;
use App\Repositories\Contracts\ISourceRepository;
use App\Repositories\DatabaseSourceRepository;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Redirect;

class PeopleController extends Controller
{
    /**
     * @var ISourceRepository | IDatabaseSourceRepository
     */
    protected $source;
    protected $isInternalSource;

    public function __construct()
    {
        if (!($source = app('SourcesResolver')->getSource()) instanceof ISourceRepository) {
            Redirect::to('/error')->with('errorMessage', 'Failed to configure data source')->send();
        }

        $this->source = $source;
        $this->isInternalSource = $source instanceof DatabaseSourceRepository;
    }

    public function index(Request $request)
    {
        $rows = $this->source->paginate((int)$request->get('page', 1));

        if (!$rows instanceof LengthAwarePaginator) {
            return redirect()->route('error.index')->with('errorMessage', 'Failed to fetch data');
        }

        $rows->setPath('/people/');

        return view('people/index', ['rows' => $rows, 'isInternalSource' => $this->isInternalSource]);
    }

    public function info($id)
    {
        $person = $this->source->info($id);

        if (!is_object($person)) {
            return view('error.partials.alert', ['message' => 'Failed to fetch data']);
        }

        return view('people/info', compact('person'));
    }

    public function delete($id)
    {
        $person = $this->source->info($id);

        if (!$person instanceof People) {
            flash('Failed to delete person')->error();

            return redirect()->route('people.index');
        }

        $person->delete();
        flash('Person was successfully deleted')->success();

        return redirect()->route('people.index');
    }

    public function edit($id)
    {
        $person = $this->source->info($id);

        if (!$person instanceof People) {
            return redirect()->route('error.index')->with('errorMessage', 'Failed to edit person');
        }

        if ($person->planet instanceof Planet) {
            $person->planet = $person->planet->id;
        }

        $genders = People::getPossibleEnumValues('gender');

        $planets = Planet::all();

        $films = Film::all()->keyBy('id')->map(function ($film) {
            return $film->title;
        });
        $selectedFilms = $person->films->map(function ($film) {
            return $film->id;
        });

        $species = Species::all()->keyBy('id')->map(function ($species) {
            return $species->name;
        });
        $selectedSpecies = $person->species->map(function ($species) {
            return $species->id;
        });

        $vehicles = Vehicle::all()->keyBy('id')->map(function ($vehicle) {
            return $vehicle->name;
        });
        $selectedVehicles = $person->vehicles->map(function ($vehicle) {
            return $vehicle->id;
        });

        $starships = Starship::all()->keyBy('id')->map(function ($starship) {
            return $starship->name;
        });
        $selectedStarships = $person->starships->map(function ($starship) {
            return $starship->id;
        });

        return view(
            'people.edit',
            compact(
                'person',
                'genders',
                'planets',
                'films',
                'selectedFilms',
                'species',
                'selectedSpecies',
                'vehicles',
                'selectedVehicles',
                'starships',
                'selectedStarships'
            )
        );
    }

    public function update($id, Request $request)
    {
        if (!$this->source->update($id, $request)) {
            return redirect()->route('error.index')->with('errorMessage', 'Failed to update person information');
        }

        flash('Person information was successfully updated')->success();

        return redirect()->route('people.index');
    }
}
