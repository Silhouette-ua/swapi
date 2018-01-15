<?php

namespace App\Models;

use Illuminate\Pagination\LengthAwarePaginator;

final class Swapi
{
    const API_PER_PAGE = 10;

    private $baseURL = 'https://swapi.co/api/';

    /**
     * Person information fields which should be loaded with separate requests
     *
     * @var array
     */
    private $fieldsToLoad = [
        'homeworld',
        'films',
        'species',
        'vehicles',
        'starships',
    ];

    public function get($request)
    {
        return $this->parseResponse(@file_get_contents($request));
    }

    public function paginate($page)
    {
        $response = $this->get($this->baseURL . 'people/?page=' . $page);

        if ($response instanceof \stdClass && $response->results) {
            $people = collect($response->results);
            $people->transform(function ($item) {
                $item->id = last(
                    array_filter(
                        explode('/', $item->url), function ($part) {
                        return strlen($part);
                    }));
                return $item;
            });

            return new LengthAwarePaginator($people, $response->count, self::API_PER_PAGE, $page);
        }

        return false;
    }

    public function peopleInfo($id)
    {
        $response = $this->get($this->baseURL . 'people/' . $id);

        if ($response instanceof \stdClass) {
            $this->loadRelatedData($response);

            return $response;
        }

        return false;
    }

    private function parseResponse($response)
    {
        return json_decode($response);
    }

    private function loadRelatedData(\stdClass &$person)
    {
        foreach ($this->fieldsToLoad as $field) {
            $context = $this;

            if (!is_array($person->{$field})) {
                $person->{$field} = [$person->{$field}];
            }

            if (empty($person->{$field})) {
                continue;
            }

            $person->{$field} = array_map(function ($url) use ($context) {
                return $context->get($url);
            }, $person->{$field});

            if ($field === 'homeworld') {
                $person->planet = reset($person->{$field});
            }
        }
    }

    public function getBaseURL()
    {
        return $this->baseURL;
    }
}
