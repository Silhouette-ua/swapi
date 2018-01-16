<?php

namespace App\Repositories\Contracts;

use Illuminate\Http\Request;

interface IDatabaseSourceRepository extends ISourceRepository
{
    public function update($id, Request $request);
}
