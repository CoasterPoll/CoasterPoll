@extends('layouts.app')

@section('title')
    New Coaster
@endsection

@section('content')
    @include('coasters.coaster.edit')
@endsection

@section('scripts')
    <script>
        $(window).on('load', function() {
            $('#categories-card').height($('#main-card').height());
        });
    </script>
@endsection