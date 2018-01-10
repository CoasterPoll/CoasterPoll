@extends('layouts.admin')

@section('title')
    All Users
@endsection

@section('content')
    <data-table endpoint="{{ route('datatables.users.index') }}"></data-table>
@endsection

@section('scripts')

@endsection