<?php

namespace ChaseH\Http\Controllers\Coasters;

use ChaseH\Models\Coasters\Manufacturer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use ChaseH\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ManufacturerController extends Controller
{
    public function view($manufacturer, $hash = null, $new_url = null) {
        // Get manufacturer from cache if possible
        if(Cache::has('man:'.$manufacturer)) {
            $man = Cache::get('man:'.$manufacturer);
        } else {
            // Get from database if possible
            try {
                $man = Manufacturer::where('abbreviation', $manufacturer)->with(['coasters', 'coasters.park'])->firstOrFail();
            } catch (ModelNotFoundException $e) {
                return abort(404);
            }
            Cache::put('man:'.$manufacturer, $man, 60);
        }

        return view('coasters.manufacturer', [
            'manufacturer' => $man,
            '_hash' => $hash,
            '_new_url' => $new_url,
        ]);
    }

    public function short($manufacturer, $tab = null) {
        try {
            $manufacturer = Manufacturer::select('abbreviation')->where('id', $manufacturer)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return abort(404);
        }

        if($tab !== null) {
            $hash = "#{$tab}";
        } else {
            $hash = null;
        }

        return $this->view($manufacturer->abbreviation, $hash, route('coasters.manufacturer', ['manufacturer' => $manufacturer->abbreviation]));
    }

    public function update(Request $request) {
        $id = $request->input('manufacturer');

        if(!Auth::user()->can('Can manage coasters')) {
            return abort(404);
        }

        // Deleting a record. So sad.
        if($id !== null && $request->input('delete') == "true") {
            try {
                $manufacturer = Manufacturer::where('id', $id)->firstOrFail();
            } catch (ModelNotFoundException $e) {
                return abort(404);
            }

            Cache::forget('man:'.$manufacturer->manufacturer);
            $manufacturer->delete();

            return redirect(route('home'))->withSuccess("We're sorry to see it go.");
        }

        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'abbreviation' => [
                'required',
                Rule::unique('manufacturers')->ignore($id)
            ],
            'location' => 'required',
            'website' => 'nullable|url',
            'rcdb_id' => 'nullable|numeric',
            'copyright' => 'nullable',
        ]);

        if($validation->fails()) {
            if($id !== null) {
                return redirect(route('coasters.manufacturer.id', ['park' => $id, 'tab' => 'edit']))->withInput()->withErrors($validation);
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

        $manufacturer = Manufacturer::updateOrCreate([
            'id' => $id,
        ],[
            'name' => $request->input('name'),
            'abbreviation' => $request->input('abbreviation'),
            'location' => $request->input('location'),
            'website' => $request->input('website'),
            'rcdb_id' => $request->input('rcdb_id'),
            'img_url' => $img_url,
            'copyright' => $request->input('copyright'),
        ]);

        Cache::forget('man:'.$manufacturer->abbreviation);

        return redirect(route('coasters.manufacturer.id', ['manufacturer' => $manufacturer->id, 'tab' => 'edit']))->withSuccess("We've made some changes!");
    }

    public function new() {
        return view('coasters.manufacturer.new');
    }
}
