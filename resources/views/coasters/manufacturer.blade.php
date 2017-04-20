@extends('layouts.app')

@section('title')
    {{ $manufacturer->name }}
@endsection

@section('content')
    <h1>{{ $manufacturer->name }}</h1>
    @can('Can manage coasters')
        <ul class="nav nav-tabs mb-4">
            <li class="nav-item">
                <a class="nav-link active" href="#main" id="main-tab">Main View</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#edit" id="edit-tab">Edit</a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="main" role="tabpanel">
                @include('coasters.manufacturer.main')
            </div>
            <div class="tab-pane" id="edit" role="tabpanel">
                @include('coasters.manufacturer.edit')
            </div>
            <div class="tab-pane" id="messages" role="tabpanel">...</div>
            <div class="tab-pane" id="settings" role="tabpanel">...</div>
        </div>
    @endcan
    @cannot('Can manage coasters')
        @include('coasters.manufacturer.main')
    @endcannot
@endsection

@section('scripts')
    @include('coasters._scripts')
    <script>
        @can('Can manage coasters')
        $('#main-tab').click(function (e) {
            e.preventDefault();
            window.location = "#main";
            $(this).tab('show');
        });
        $('#edit-tab').click(function (e) {
            e.preventDefault();
            window.location = "#edit";
            $(this).tab('show');
        });

        $('.nav-link').on('click', function() {
            $('#categories-card').height($('#main-card').height());
        });
        $(window).on('load', function() {
            $('#categories-card').height($('#main-card').height());
        });

        var hash = window.location.hash;
        if(hash) {
            $(hash+'-tab').tab('show');
        }
        @endcan
    </script>
@endsection