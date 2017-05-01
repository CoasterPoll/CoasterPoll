<?php

namespace ChaseH\Http\Controllers\Users;

use ChaseH\Models\Permission;
use ChaseH\Models\Role;
use ChaseH\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use ChaseH\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;

class PermissionsController extends Controller
{
    public function deleteUserRole(Request $req) {
        try {
            $user = User::where('id', $req->input('user'))->firstOrFail();
            $role = Role::where('id', $req->input('role'))->firstOrfail();
        } catch (ModelNotFoundException $e) {
            return back()->withWarning("Can't find user or role.");
        }

        $user->withdrawRoleTo($role->name);

        return back()->withSuccess("Removed \"{$role->name}\" from {$user->name}.");
    }

    public function postUserRole(Request $req) {
        try {
            $user = User::where('id', $req->input('user'))->firstOrFail();
            $role = Role::where('id', $req->input('role'))->firstOrfail();
        } catch (ModelNotFoundException $e) {
            return back()->withWarning("Can't find user or role.");
        }

        $user->giveRoleTo($role->name);

        return back()->withSuccess("Added \"{$role->name}\" role to {$user->name}.");
    }

    public function postUserPermission(Request $req) {
        try {
            $user = User::where('id', $req->input('user'))->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return back()->withWarning("Can't find user.");
        }

        $user->givePermissionTo($req->input('permissions'));

        Cache::forget('perms:'.$user->id);

        return back()->withSuccess("Updated {$user->name}'s individual permissions.");
    }

    public function getRoles($id = null) {
        if($id !== null) {
            try {
                $role = Role::where('id', $id)->with('permissions')->firstOrFail();
            } catch (ModelNotFoundException $e) {
                $role = null;
            }
        } else {
            $role = null;
        }

        return view('admin.users.roles', [
            'roles' => Role::get(),
            'single' => $role,
            'permissions' => Permission::get(),
        ]);
    }

    public function postRole(Request $request) {
        $this->validate($request, [
            'name' => "required"
        ]);

        $role = Role::updateOrCreate([
            'id' => $request->input('role')
        ], [
            'name' => $request->input('name'),
            'default' => $request->input('default'),
        ]);

        $ids = $request->input('permissions');
        if(count($ids) == 0) {
            $role->permissions()->detach();
            return back()->withSuccess("Removed all permissions. They won't need 'em.");
        }
        $permissions = Permission::whereIn('id', $ids)->get();

        $role->permissions()->sync($permissions);

        foreach($permissions as $permission) {
            Cache::forget('perm-role:'.$permission->id);
        }

        return back()->withSuccess("We made changes to {$role->name}! Hopefully for the better...");
    }

    public function deleteRole(Request $request) {
        try {
            $role = Role::where('id', $request->input('role'))->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return back()->withWarning("Could not find that role.");
        }

        $role->delete();

        return back()->withSuccess("It's gone!");
    }
}
