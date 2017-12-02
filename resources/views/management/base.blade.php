@extends('base')
@section('content-full')
    <div class="container">
        <div class="row">
            <div class="col-3">
                @include('management.menu.menu')
            </div>
            <div class="col-9">
                @yield('content')
            </div>
        </div>
    </div>
@endsection