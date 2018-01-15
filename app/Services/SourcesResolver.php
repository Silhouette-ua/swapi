<?php

namespace App\Services;

use App\Models\Database\People;
use App\Repositories\SwapiSourceRepository;
use App\Repositories\Contracts\ISourceRepository;
use App\Repositories\DatabaseSourceRepository;

class SourcesResolver
{
    protected $isDatabaseFilled = false;

    public function __construct()
    {
        $this->isDatabaseFilled = !!People::count();
    }

    /**
     * @return ISourceRepository
     */
    public function getSource()
    {
        return $this->isDatabaseFilled ? new DatabaseSourceRepository() : new SwapiSourceRepository();
    }
}
