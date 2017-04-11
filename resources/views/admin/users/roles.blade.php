@extends('layouts.admin')

@section('title')
    User Roles
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">All Roles</h2>
                </div>
                <div class="card-block">
                    <table class="table table-striped">
                        @foreach($roles as $role)
                            <tr>
                                <th>{{ $role->name }}</th>
                                <td class="text-right">
                                    <form action="{{ route('admin.user.roles.delete') }}" method="post">
                                        {{ method_field('DELETE') }}
                                        {{ csrf_field() }}
                                        <a href="{{ route('admin.user.roles', ['id' => $role->id]) }}">Edit</a>
                                        <button type="submit" name="role" value="{{ $role->id  }}" class="btn btn-sm btn-danger ml-4"><i class="fa fa-trash"></i> Remove</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
                <div class="card-footer text-right">
                    <a href="{{ route('admin.user.roles') }}">New</a>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <form action="{{ route('admin.user.roles.post') }}" method="post" autocomplete="nope">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Makin' Changes @if(isset($single)) to {{ $single->name }} @endif</h2>
                    </div>
                    <div class="card-block row">
                        <div class="col-md-6">
                            <fieldset class="form-group">
                                <label for="name">Name</label>
                                <input type="text" id="name" name="name" autocomplete="nope" class="form-control" @if(isset($single)) value="{{ $single->name }}"@else autofocus @endif tabindex="1">
                            </fieldset>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-striped table-sm">
                                <tbody>
                                @foreach($permissions as $permission)
                                    <tr>
                                        <td>{{ $permission->name }}</td>
                                        <td class="form-check">
                                            <label class="form-check-label">
                                                <input class="form-check-input" type="checkbox" name="permissions[]}" value="{{ $permission->id }}"
                                                        @if(!is_null($single) && in_array($permission->id, $single->permissions->pluck('id')->toArray())) checked @endif
                                                        tabindex="{{ $loop->iteration + 1 }}"
                                                >
                                            </label>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        {{ csrf_field() }}
                        @if(isset($single))
                            <input type="hidden" name="role" value="{{ $single->id }}">
                        @endif
                        <button type="submit" class="btn btn-primary ml-3 my-auto"><i class="fa fa-save"></i> Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection