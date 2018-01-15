<?php

namespace App\Repositories\Contracts;

use Illuminate\Http\Request;

interface IDatabaseSourceRepository
{
    public function update($id, Request $request);
}
