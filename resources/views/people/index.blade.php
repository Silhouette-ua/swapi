@extends('layouts.app')

@section('content')
    <div class="container custom">
        @include('flash::message')
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">People
                        ({{ $isInternalSource ? 'Internal database storage' : 'External API storage' }})
                    </div>
                    <div class="panel-body">
                        <div class="container">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Birth year</th>
                                    <th>Height</th>
                                    <th>Mass</th>
                                    <th>Skin color</th>
                                    <th>Hair color</th>
                                    <th>Eye color</th>
                                    <th class="actions-column">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($rows as $row)
                                    <tr>
                                        <td>{{ $row->name }}</td>
                                        <td>{{ $row->birth_year ?: 'unknown' }}</td>
                                        <td>{{ $row->height ?: 'unknown' }}</td>
                                        <td>{{ $row->mass ?: 'unknown' }}</td>
                                        <td>{{ $row->skin_color ?: 'unknown' }}</td>
                                        <td>{{ $row->hair_color ?: 'unknown' }}</td>
                                        <td>{{ $row->eye_color ?: 'unknown'}}</td>
                                        <td class="actions-column">
                                            <div class="btn-group">
                                                <button class="btn btn-default people-info" title="Show info" data-url="{{ route('people.info', $row->id) }}">
                                                    <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                </button>
                                                @if(Auth::check() && $isInternalSource)
                                                    <button class="btn btn-default people-edit" title="Edit entry" data-url="{{ route('people.edit', $row->id) }}">
                                                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                    </button>
                                                    <button class="btn btn-default people-delete" title="Delete entry" data-url="{{ route('people.delete', $row->id) }}">
                                                        <i class="fa fa-times" aria-hidden="true"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination">
                                {{ $rows->links() }}
                            </div>
                        </div>
                        <form name="people-delete-form" id="people-delete-form" method="POST" action="">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="_method" value="DELETE">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="people-info-modal" class="modal fade">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Information</h5>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/grid.js') }}"></script>
@endsection
