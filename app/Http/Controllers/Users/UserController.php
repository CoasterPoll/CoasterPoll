<?php

namespace ChaseH\Http\Controllers\Users;

use ChaseH\Models\Permission;
use ChaseH\Models\Role;
use ChaseH\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use ChaseH\Http\Controllers\Controller;

class UserController extends Controller
{
    public function profile($user) {
        try {
            $user = User::where('name', $user)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return abort(404);
        }

        dd($user);
    }

    public function getUser($id) {
        try {
            $user = User::where('id', $id)->with('permissions', 'roles')->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return abort(404);
        }

        return view('admin.users.user', [
            'user' => $user,
            'roles' => Role::get(),
            'permissions' => Permission::with('roles')->get(),
            'perm_array' => $user->permissions->pluck('id')->toArray(),
        ]);
    }

    public function postUser(Request $request) {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'email|required',
        ]);

        try {
            $user = User::where('id', $request->input('user'))->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return abort(404);
        }

        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email')
        ]);

        return back()->withSuccess("Yeah! We made some changes, like 'em?");
    }
}
