<?php

namespace ChaseH\Http\Controllers;

use ChaseH\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard() {
        return view('console');
    }

    public function search(Request $request) {
        if($request->input('q') == null) {
            return view('admin.search');
        }

        // Search for users
        $users = User::look($request->input('q'));

        // Gather results
        $results = collect($users);

        return view('admin.search', [
            'results' => $results,
            'query' => $request->input('q'),
        ]);
    }
}
