@extends('layouts.app')

@section('content')
    <div class="container custom">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Edit person</div>
                    <div class="panel-body">
                        <div class="container">
                            <form name="people-edit-form" method="POST" action="{{  route('people.update', $person->id) }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="_method" value="PATCH">

                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ $person->name }}">
                                </div>

                                <div class="form-group">
                                    <label for="gender">Gender</label>
                                    <select class="form-control" name="gender">
                                        @foreach($genders as $gender)
                                            <option value="{{ $gender }}" {{ $gender === $person->gender ? 'selected' : '' }}>{{ $gender }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="birth_year">Birth year</label>
                                    <input type="text" class="form-control" id="birth_year" name="birth_year" value="{{ $person->birth_year }}">
                                </div>

                                <div class="form-group">
                                    <label for="height">Height</label>
                                    <input type="number" step="1" min="0" class="form-control" id="height" name="height" value="{{ $person->height }}">
                                </div>

                                <div class="form-group">
                                    <label for="mass">Mass</label>
                                    <input type="number" step="0.0001" min="0" class="form-control" id="mass" name="mass" value="{{ $person->mass }}">
                                </div>

                                <div class="form-group">
                                    <label for="homeworld">Homeworld</label>
                                    <select class="form-control" name="homeworld">
                                        @foreach($planets as $planet)
                                            <option value="{{ $planet->id }}" {{ $planet->id == $person->planet ? 'selected' : '' }}>{{ $planet->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="skin_color">Skin color</label>
                                    <input type="text" class="form-control" id="skin_color" name="skin_color" value="{{ $person->skin_color }}">
                                </div>

                                <div class="form-group">
                                    <label for="hair_color">Hair color</label>
                                    <input type="text" class="form-control" id="hair_color" name="hair_color" value="{{ $person->hair_color }}">
                                </div>

                                <div class="form-group">
                                    <label for="name">Eye_color</label>
                                    <input type="text" class="form-control" id="eye_color" name="eye_color" value="{{ $person->eye_color }}">
                                </div>

                                <div class="form-group">
                                    <label for="films">Species</label>
                                    {!! Multiselect::select('species', $species, $selectedSpecies,   ['class' => 'form-control multiselect']) !!}
                                </div>

                                <div class="form-group">
                                    <label for="films">Films</label>
                                    {!! Multiselect::select('films', $films, $selectedFilms,   ['class' => 'form-control multiselect']) !!}
                                </div>

                                <div class="form-group">
                                    <label for="films">Vehicles</label>
                                    {!! Multiselect::select('vehicles', $vehicles, $selectedVehicles,   ['class' => 'form-control multiselect']) !!}
                                </div>

                                <div class="form-group">
                                    <label for="films">Starships</label>
                                    {!! Multiselect::select('starships', $starships, $selectedStarships,   ['class' => 'form-control multiselect']) !!}
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-default">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
