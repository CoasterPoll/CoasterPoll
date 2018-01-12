@extends('layouts.admin')

@section('title')
    User Index
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Handle?</th>
                        <th>Email</th>
                        <th>Joined At</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->handle ?? "N/a" }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->created_at->format('Y-m-d g:ia') }}</td>
                            <td>
                                @if(!is_null($user->handle))
                                    <a href="{{ route('profile', ['handle' => $user->handle]) }}" class="btn btn-outline-info btn-sm">Profile</a>
                                @endif
                                @if(\Illuminate\Support\Facades\Auth::user()->can('Can manage users'))
                                    <a href="{{ route('admin.user', ['id' => $user->id]) }}" class="btn btn-outline-primary btn-sm">Admin</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $users->links('vendor.pagination.bootstrap-4') }}
        </div>
    </div>
@endsection

@section('scripts')

@endsection