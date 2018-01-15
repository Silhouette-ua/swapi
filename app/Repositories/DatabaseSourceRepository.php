<?php

namespace App\Repositories;

use App\Models\Database\Film;
use App\Models\Database\People;
use App\Repositories\Contracts\IDatabaseSourceRepository;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class DatabaseSourceRepository extends SourceRepository implements IDatabaseSourceRepository
{
    /**
     * @param int $page
     * @return LengthAwarePaginator
     */
    public function paginate($page)
    {
        return People::noEagerLoads()->paginate(static::DEFAULT_PER_PAGE, ['*'], 'page', $page);
    }

    public function info($id)
    {
        return People::where('id', $id)->first();
    }

    public function update($id, Request $request)
    {
        $person = $this->info($id);

        if (!$person instanceof People) {
            return redirect()->route('error.index')->with('errorMessage', 'Failed to update person information');
        }

        DB::beginTransaction();

        try {
            $info = $request->except(['_token', '_method']);
            $person->fill($info);
            $person->save();
            $person->films()->sync($info['films']);
            $person->species()->sync($info['species']);
            $person->vehicles()->sync($info['vehicles']);
            $person->starships()->sync($info['starships']);
        } catch (QueryException $exception) {
            DB::rollback();

            return false;
        }

        DB::commit();

        return true;
    }

    protected function setSource()
    {
        $this->source = new People();
    }
}
