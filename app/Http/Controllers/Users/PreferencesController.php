<?php

namespace ChaseH\Http\Controllers\Users;

use ChaseH\Jobs\UpdateDemographicCity;
use Illuminate\Http\Request;
use ChaseH\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PreferencesController extends Controller
{
    public function demographics() {
        $user = Auth::user()->load('demographics');

        // If there isn't already a demographic object on the user, create one
        if($user->demographics == null) {
            $demo = $user->demographics()->create([
                'age_range' => 0,
            ]);

            $user->demographic_id = $demo->id;
            $user->save();

            $user = $user->load('demographics');
        }

        return view('users.demographics', [
            'user' => $user,
        ]);
    }

    public function saveDemographics(Request $request) {
        $this->validate($request, [
            'age_range' => 'nullable|numeric',
            'gender' => 'nullable|numeric',
            'location' => 'nullable|string',
            'park_visits' => 'numeric|nullable',
            'unique_parks' => 'numeric|nullable',
        ]);

        $user = Auth::user()->load('demographics');

        if($request->input('location') == $user->demographics->city) {
            $lat = $user->demographics->latitude;
            $long = $user->demographics->longitude;
        } else {
            $lat = null;
            $long = null;
        }

        $user->demographics()->update([
            'age_range' => $request->input('age_range'),
            'gender' => $request->input('gender'),
            'city' => $request->input('location'),
            'park_visits' => $request->input('park_visits'),
            'unique_parks' => $request->input('unique_parks'),
            'latitude' => $lat,
            'longitude' => $long,
        ]);

        $this->dispatch(new UpdateDemographicCity());

        return back()->withSuccess("Thanks for that!");
    }

    public function settings() {
        return view('users.preferences', [
            'user' => Auth::user(),
        ]);
    }

    public function updateSettings(Request $request) {
        $user = Auth::user();

        if($request->input('edit-password') == 'true') {
            $v = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'handle' => 'required|string|max:255|alpha_dash|unique:users,handle,'.$user->id,
                'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
                'password' => 'required|string|min:6|confirmed',
            ]);

            $v->sometimes('old_password', 'required|string|min:6', function($input) use ($user) {
                return $user->password !== null;
            });

            if($v->fails()) {
                return back()->withErrors($v)->withInput();
            }

            if($user->password !== null && !Hash::check($request->input('old_password'), $user->getAuthPassword())) {
                return back()->withDanger("That password doesn't match your current one.");
            }

            $user->password = bcrypt($request->input('password'));
        } else {
            $this->validate($request, [
                'name' => 'required|string|max:255',
                'handle' => 'required|string|max:255|alpha_dash|unique:users,handle,'.$user->id,
                'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            ]);
        }

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->handle = $request->input('handle');

        $user->save();

        return back()->withSuccess("Done!");
    }
}
