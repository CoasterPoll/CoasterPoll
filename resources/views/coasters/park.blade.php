@extends('layouts.app')

@section('title')
    {{ $park->name }}
@endsection

@section('content')
    <h1>{{ $park->name }}</h1>
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
                @include('coasters.park.main')
            </div>
            <div class="tab-pane" id="edit" role="tabpanel">
                @include('coasters.park.edit')
            </div>
            <div class="tab-pane" id="messages" role="tabpanel">...</div>
            <div class="tab-pane" id="settings" role="tabpanel">...</div>
        </div>
    @endcan
    @cannot('Can manage coasters')
        @include('coasters.park.main')
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

        var hash = window.location.hash;
        if(hash) {
            $(hash+'-tab').tab('show');
        }
        @endcan
    </script>
@endsection