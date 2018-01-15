<?php

namespace App\Repositories\Contracts;

interface ISourceRepository
{
    const DEFAULT_PER_PAGE = 10;

    public function paginate($page);

    public function info($id);

    public function getPerPage();

    public function setPerPage($perPage);
}
