<?php

namespace ChaseH\Http\Controllers\Users;

use ChaseH\Models\Coasters\Park;
use ChaseH\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use ChaseH\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ProfileController extends Controller
{
    public function profile($handle = null) {
        if($handle == null) {
            $handle = Auth::user()->handle;
        }

        try {
            $user = Cache::remember('u:'.$handle, 60, function() use ($handle) {
                return User::where('handle', $handle)->with('links')->firstOrFail();
            });
        } catch (ModelNotFoundException $e) {
            return abort(404);
        }

        $topCoasters = Cache::remember('ucoasters:'.$user->id, 15, function() use ($user) {
            return $user->ranked()->take(5)->orderBy('rank')->with('coaster', 'coaster.park')->get();
        });

        $parks = Cache::remember('uparkcount:'.$user->id, 15, function() use ($user) {
            $ids = array_unique(array_pluck($user->ridden, 'park_id'));
            return Park::whereIn('id', $ids)->get();
        });

        return view('profile.profile', [
            'user' => $user,
            'topCoasters' => $topCoasters,
            'current' => (bool) $user->id == Auth::id(),
            'parks' => $parks,
        ]);
    }

    public function rankings($handle = null) {
        if($handle == null) {
            $handle = Auth::user()->handle;
        }

        try {
            $user = Cache::remember('u:'.$handle, 60, function() use ($handle) {
                return User::where('handle', $handle)->with('links')->firstOrFail();
            });
        } catch (ModelNotFoundException $e) {
            return abort(404);
        }

        $rankings = $user->ranked()->orderby('rank')->with('coaster', 'coaster.park', 'coaster.manufacturer')->get();

        return view('profile.rankings', [
            'user' => $user,
            'rankings' => $rankings,
        ]);
    }
}
