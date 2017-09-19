@extends('layouts.app')

@section('title')
Home
@endsection

@section('content')
    {!! $content !!}
@endsection

@section('scripts')
    <script>
        @auth
            $('#ridden-example').on('click', function() {
               toastr.warning("Sorry, that's not a real button.")
            });
        @endauth
    </script>
@endsection