@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Error</div>
                    <div class="panel-body">
                        @include('error.partials.alert')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
