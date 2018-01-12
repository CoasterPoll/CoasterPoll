@extends('layouts.admin')

@section('title')
Dashboard
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-users"></i> Users
                </div>
                <div class="card-block">
                    <table class="table">
                        <tr>
                            <th>Total Users</th>
                            <td>{{ $counts->user_objects }}</td>
                        </tr>
                        <tr>
                            <th>Active Users</th>
                            <td>{{ $counts->user_active }}</td>
                        </tr>
                        <tr>
                            <th>New* Users</th>
                            <td>{{ $counts->user_new }}</td>
                        </tr>
                    </table>
                    <small>*Created in the last two weeks.</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-thumbs-up"></i> Stats
                </div>
                <div class="card-block">
                    <table class="table">
                        <tr>
                            <th>Total Coasters</th>
                            <td>{{ $counts->coasters }}</td>
                        </tr>
                        <tr>
                            <th>Total Parks</th>
                            <td>{{ $counts->parks }}</td>
                        </tr>
                        <tr>
                            <th>Total Manufacturers</th>
                            <td>{{ $counts->manufacturers }}</td>
                        </tr>
                        <tr>
                            <th>Coasters Ridden</th>
                            <td>{{ $counts->ridden }}</td>
                        </tr>
                        <tr>
                            <th>Coasters Ranked</th>
                            <td>{{ $counts->ranked }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-search"></i> Searching
                </div>
                <div class="card-block">
                    <table class="table">
                        <tr>
                            <th>Total Searches</th>
                            <td>{{ $searches['searches'] }}</td>
                        </tr>
                        <tr>
                            <th>Total Operations</th>
                            <td>{{ $searches['operations'] }}</td>
                        </tr>
                        <tr>
                            <th>Total Records</th>
                            <td>{{ $searches['records'] }}</td>
                        </tr>
                    </table>
                    <small>Over the last month.</small>
                </div>
            </div>
        </div>
    </div>
@endsection
