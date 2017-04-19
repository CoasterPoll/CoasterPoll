<?php

namespace ChaseH\Http\Controllers\Coasters;

use ChaseH\Models\Coasters\Park;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use ChaseH\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ParkController extends Controller
{
    public function view($park, $hash = null, $new_url = null) {
        // Get manufacturer from cache if possible
        if(Cache::has('park:'.$park)) {
            $pk = Cache::get('park:'.$park);
        } else {
            // Get from database if possible
            try {
                $pk = Park::where('short', $park)->with(['coasters', 'coasters.manufacturer'])->firstOrFail();
            } catch (ModelNotFoundException $e) {
                return abort(404);
            }
            Cache::put('park:'.$park, $pk, 60);
        }

        return view('coasters.park', [
            'park' => $pk,
            '_hash' => $hash,
            '_new_url' => $new_url,
        ]);
    }

    public function short($park) {
        try {
            $park = Park::select('short')->where('id', $park)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return abort(404);
        }

        return $this->view($park->short, "#edit", route('coasters.park', ['park' => $park->short]));
    }

    public function update(Request $request) {
        $id = $request->input('park');

        /* This permissions mess...
        If they 'Can manage coasters'.
            - Yes: continue
            - No: check if they can represent a park and we know the park ID.
                - Yes: continue
                - No: quit
        */

        if(!Auth::user()->can('Can manage coasters')) {
            if($id !== null && Auth::user()->can('Can represent park')) {
                if(!Auth::user()->parks->contains('id', $id)) {
                    return abort(404);
                }
            } else {
                return abort(404);
            }
        }

        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'short' => [
                'required',
                Rule::unique('parks')->ignore($id)
            ],
            'city' => 'required',
            'country' => 'required',
            'website' => 'nullable|url',
            'rcdb_id' => 'nullable|numeric',
            'copyright' => 'nullable',
        ]);

        if($validation->fails()) {
            if($id !== null) {
                return redirect(route('coasters.park.id', ['park' => $id]))->withInput()->withErrors($validation);
            } else {
                return back(400)->withErrors($validation)->withInput();
            }
        }

        // Check for an uploaded image
        if(false) {
            $img_url = "";
        } else {
            $img_url = null;
        }

        $park = Park::updateOrCreate([
            'id' => $id,
        ],[
            'name' => $request->input('name'),
            'short' => $request->input('short'),
            'city' => $request->input('city'),
            'country' => $request->input('country'),
            'website' => $request->input('website'),
            'rcdb_id' => $request->input('rcdb_id'),
            'img_url' => $img_url,
            'copyright' => $request->input('copyright'),
        ]);

        Cache::forget('park:'.$park->short);

        return redirect(route('coasters.park.id', ['park' => $park->id]))->withSuccess("We've made some changes!");
    }
}