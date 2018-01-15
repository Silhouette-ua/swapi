<?php

namespace App\Models\Database;

class People extends BaseModel
{
    const UPDATED_AT = 'edited';

    protected $table = 'people';
    protected $fillable = ['name', 'height', 'mass', 'hair_color', 'skin_color', 'eye_color', 'birth_year', 'gender', 'homeworld'];

    public function planet()
    {
        return $this->belongsTo('App\Models\Database\Planet', 'homeworld', 'id');
    }

    public function films()
    {
        return $this->belongsToMany('App\Models\Database\Film', 'people_to_films', 'people_id', 'film_id');
    }

    public function species()
    {
        return $this->belongsToMany('App\Models\Database\Species', 'people_to_species', 'people_id', 'species_id');
    }

    public function vehicles()
    {
        return $this->belongsToMany('App\Models\Database\Vehicle', 'people_to_vehicles', 'people_id', 'vehicle_id');
    }

    public function starships()
    {
        return $this->belongsToMany('App\Models\Database\Starship', 'people_to_starships', 'people_id', 'starship_id');
    }
}
