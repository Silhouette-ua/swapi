<?php

namespace App\Repositories;

use App\Repositories\Contracts\ISourceRepository;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class SourceRepository implements ISourceRepository
{
    protected $source;
    protected $perPage;

    /**
     * @param $page
     * @return LengthAwarePaginator
     */
    abstract public function paginate($page);

    abstract protected function setSource();

    public function __construct($perPage = null)
    {
        $this->perPage = $perPage ?: static::DEFAULT_PER_PAGE;
        $this->setSource();
    }

    public function getPerPage()
    {
        return $this->perPage;
    }

    public function setPerPage($perPage)
    {
        $this->perPage = $perPage;

        return $this;
    }
}
