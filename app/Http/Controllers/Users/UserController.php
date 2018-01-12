<?php

namespace ChaseH\Http\Controllers\Users;

use ChaseH\Models\Permission;
use ChaseH\Models\Role;
use ChaseH\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use ChaseH\Http\Controllers\Controller;

class UserController extends Controller
{
    use SendsPasswordResetEmails;

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
            $user = User::where('id', $id)->with('permissions', 'roles')->withTrashed()->firstOrFail();
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

    public function lockAccount(Request $request) {
        try {
            $user = User::where('id', $request->input('user'))->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return abort(404);
        }

        $user->lockAccount();

        return redirect(route('admin.user', ['id' => $user->id]))->withSuccess("Account locked.");
    }

    public function unlockAccount(Request $request) {
        try {
            $user = User::where('id', $request->input('user'))->withTrashed()->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return abort(404);
        }

        $user->unlockAccount();

        return redirect(route('admin.user', ['id' => $user->id]))->withSuccess("Account unlocked!");
    }

    public function resetPassword(Request $request) {
        $response = $this->sendResetLinkEmail($request);

        return back()->withSuccess("Done.");
    }

    public function index() {
        $users = User::paginate(35);

        return view('admin.users.index', [
            'users' => $users,
        ]);
    }
}
