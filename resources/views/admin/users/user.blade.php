@extends('layouts.admin')

@section('title')
    {{ $user->name }}'s Account
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h2 class="card-title">Details</h2>
                </div>
                <form action="{{ route('admin.user.post') }}" method="post" autocomplete="nope">
                    <div class="card-block">
                        <fieldset>
                            <label for="name">Name</label>
                            <input type="text" id="name" name="name" value="{{ $user->name }}" class="form-control">
                        </fieldset>
                        <fieldset>
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" value="{{ $user->email }}" class="form-control">
                        </fieldset>
                    </div>
                    <div class="card-footer text-right">
                        {{ csrf_field() }}
                        <input type="hidden" name="user" value="{{ $user->id }}">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title"><em>Nadda</em></h2>
                </div>
                <div class="card-block">
                    @if($user->deleted_at == null)
                        <form action="{{ route('admin.user.lock.post') }}" class="mb-4" method="post">
                            <div class="row">
                                <div class="col-sm-9">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="user" value="{{ $user->id }}">
                                    <p>Removes permissions and soft deletes account.</p>
                                </div>
                                <div class="col-sm-3">
                                    <button type="submit" class="btn btn-outline-danger confirm-form"><i class="fa fa-lock"></i> Lock</button>
                                </div>
                            </div>
                        </form>
                    @else
                        <form action="{{ route('admin.user.unlock.post') }}" method="post">
                            <div class="row">
                                <div class="col-sm-9">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="user" value="{{ $user->id }}">
                                    <p>Restores account.</p>
                                </div>
                                <div class="col-sm-3">
                                    <button type="submit" class="btn btn-outline-success confirm-form"><i class="fa fa-unlock"></i> Unlock</button>
                                </div>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h2 class="card-title">Roles</h2>
                </div>
                <div class="card-block">
                    <form action="{{ route('admin.user.role.delete') }}" method="post">
                        <table class="table">
                            <tbody>
                            @foreach($user->roles as $role)
                                <tr>
                                    <td>@can('Can manage roles')<a href="{{ route('admin.user.roles', ['id' => $role->id]) }}">@endcan {{ $role->name }}@can('Can manage roles') </a> @endcan</td>
                                    <td class="text-right">
                                        <button type="submit" name="role" value="{{ $role->id }}" class="btn btn-sm btn-warning"><i class="fa fa-trash-o"></i> Remove</button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                        <input type="hidden" name="user" value="{{ $user->id }}">
                    </form>
                </div>
                <div class="card-footer">
                    <form class="form-inline pull-right" action="{{ route('admin.user.role.post') }}" method="post">
                        <select class="form-control mx-2" name="role">
                            <option value="0" disabled selected>Choose Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}"
                                        @if(in_array($role->id, $user->roles->pluck('id')->toArray()))
                                        disabled
                                        @endif
                                >{{ $role->name }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-handshake-o"></i> Grant</button>
                        <input type="hidden" name="user" value="{{ $user->id }}">
                        {{ csrf_field() }}
                    </form>
                </div>
            </div>
            <div class="card my-4">
                <div class="card-header">
                    <h2 class="card-title">Permissions</h2>
                </div>
                <form action="{{ route('admin.user.permission.post') }}" method="post">
                    <div class="card-block">
                        <table class="table table-striped table-sm">
                            <tbody>
                                @foreach($permissions as $permission)
                                    <tr>
                                        <td class="@if($user->hasPermissionThroughRole($permission)) table-info @endif">{{ $permission->name }}</td>
                                        <td class="form-check">
                                            <label class="form-check-label">
                                                <input class="form-check-input" type="checkbox" name="permissions[]}" value="{{ $permission->name }}"
                                                       @if(in_array($permission->id, $perm_array)) checked @endif>
                                            </label>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <small>*Highlights indicate permissions granted from one of the users roles.</small>
                    </div>
                    <div class="card-footer text-right">
                        {{ csrf_field() }}
                        <input type="hidden" name="user" value="{{ $user->id }}">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection