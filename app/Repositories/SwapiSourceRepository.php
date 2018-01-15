<?php

namespace App\Repositories;

use App\Models\Swapi;
use Illuminate\Pagination\LengthAwarePaginator;

class SwapiSourceRepository extends SourceRepository
{
    /**
     * @param int $page
     * @return LengthAwarePaginator
     */
    public function paginate($page)
    {
        return $this->source->paginate($page);
    }

    public function info($externalId)
    {
        return $this->source->peopleInfo($externalId);
    }

    protected function setSource()
    {
        $this->source = new Swapi();
    }
}
