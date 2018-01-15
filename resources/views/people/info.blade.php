<table class="table table-bordered person-info">
    <tbody>
    <tr>
        <td class="bold">Name:</td>
        <td>{{ $person->name }}</td>
    </tr>
    <tr>
        <td class="bold">Gender:</td>
        <td>{{ $person->gender }}</td>
    </tr>
    <tr>
        <td class="bold">Birth year:</td>
        <td>{{ $person->birth_year ?: 'unknown'}}</td>
    </tr>
    <tr>
        <td class="bold">Height:</td>
        <td>{{ $person->height ?: 'unknown'}}</td>
    </tr>
    <tr>
        <td class="bold">Mass:</td>
        <td>{{ $person->mass ?: 'unknown' }}</td>
    </tr>
    <tr>
        <td class="bold">Homeworld:</td>
        <td>{{ $person->planet->name }}</td>
    </tr>
    <tr>
        <td class="bold">Skin color:</td>
        <td>{{ $person->skin_color ?: 'unknown'}}</td>
    </tr>
    <tr>
        <td class="bold">Hair color:</td>
        <td>{{ $person->hair_color ?: 'unknown' }}</td>
    </tr>
    <tr>
        <td class="bold">Eye color:</td>
        <td>{{ $person->eye_color ?: 'unknown' }}</td>
    </tr>
    <tr>
        <td class="bold">Species:</td>
        <td>
            <ul class="person-props-list">
                @forelse($person->species as $species)
                    <li>{{ $species->name }} ({{ $species->classification }})</li>
                @empty
                    -
                @endforelse
            </ul>
        </td>
    </tr>
    <tr>
        <td class="bold">Films:</td>
        <td>
            <ul class="person-props-list">
                @forelse($person->films as $film)
                    <li>{{ $film->title }} (by {{ $film->producer }})</li>
                @empty
                    -
                @endforelse
            </ul>
        </td>
    </tr>
    <tr>
        <td class="bold">Vehicles:</td>
        <td>
            <ul class="person-props-list">
                @forelse($person->vehicles as $vehicle)
                    @if($vehicle->model !== $vehicle->name)
                        <li>{{ $vehicle->model }} "{{ $vehicle->name }}"</li>
                    @else
                        <li>{{ $vehicle->model }}</li>
                    @endif
                @empty
                    -
                @endforelse
            </ul>
        </td>
    </tr>
    <tr>
        <td class="bold">Starships:</td>
        <td>
            <ul class="person-props-list">
                @forelse($person->starships as $starship)
                    @if($starship->model !== $starship->name)
                        <li>{{ $starship->model }} "{{ $starship->name }}"</li>
                    @else
                        <li>{{ $starship->model }}</li>
                    @endif
                @empty
                    -
                @endforelse
            </ul>
        </td>
    </tr>
    </tbody>
</table>
