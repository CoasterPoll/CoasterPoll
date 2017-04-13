@extends('layouts.app')

@section('title')
    Big List of Coasters
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <table class="table table-striped">
                <tbody>
                    @foreach($coasters as $coaster)
                        <tr>
                            <td>{{ $coaster->name }}</td>
                            <td>{{ $coaster->park->name }} <small>{{ $coaster->park->city }}</small></td>
                            <td>{{ $coaster->manufacturer->name }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $coasters->links('vendor.pagination.bootstrap-4') }}
        </div>
    </div>
@endsection

@section('scripts')
    @include('coasters._scripts')
@endsection