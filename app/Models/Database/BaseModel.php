<?php

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BaseModel extends Model
{
    /**
     * Load data ignoring related entities
     *
     * @param $query
     * @return mixed
     */
    public function scopeNoEagerLoads($query)
    {
        return $query->setEagerLoads([]);
    }

    /**
     * Makes decimal number compatible with MySQL
     */
    public static function replaceDecimalDelimiter($number)
    {
        return is_null($number) ? $number : str_replace(',', '.', $number);
    }

    /**
     * Retrieves the acceptable enum fields for a column
     *
     * @param string $column Column name
     *
     * @return array
     */
    public static function getPossibleEnumValues($column)
    {
        $instance = new static;

        $enumStr = DB::select(DB::raw('SHOW COLUMNS FROM ' . $instance->getTable() . ' WHERE Field = "' . $column . '"'))[0]->Type;

        preg_match_all("/'([^']+)'/", $enumStr, $matches);

        return isset($matches[1]) ? $matches[1] : [];
    }
}
