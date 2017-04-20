<?php

namespace ChaseH\Http\Controllers\Coasters;

use ChaseH\Models\Coasters\Category;
use ChaseH\Models\Coasters\Coaster;
use ChaseH\Models\Coasters\Manufacturer;
use ChaseH\Models\Coasters\Park;
use ChaseH\Models\Coasters\Type;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use ChaseH\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class CoasterController extends Controller
{
    public function view($pk, $cstr) {
        if(Cache::has($pk.":".$cstr)) {
            $coaster = Cache::get($pk.":".$cstr);
        } else {
            try {
                $coaster = Coaster::where('slug', $cstr)->whereHas('park', function($query) use ($pk) {
                    $query->where('parks.short', $pk);
                })->with('park', 'categories', 'type', 'manufacturer')->firstOrFail();

                Cache::put($pk.":".$cstr, $coaster, 120);
            } catch (ModelNotFoundException $e) {
                return abort(404);
            }
        }

        if(Auth::check() && Auth::user()->can('Can manage coasters')) {
            $categories = Cache::remember('all_categories', 120, function() {
                return Category::select('id', 'name')->get();
            });
            $types = Cache::remember('all_types', 120, function() {
                return Type::select('id', 'name')->get();
            });
            $manufacturers = Cache::remember('all_manu', 120, function() {
                return Manufacturer::select('id', 'name')->get();
            });
            $parks = Cache::remember('all_parks', 120, function() {
                return Park::select('id', 'name')->get();
            });
        } else {
            $categories = null;
            $types = null;
            $manufacturers = null;
            $parks = null;
        }

        return view('coasters.coaster', [
            'coaster' => $coaster,
            'categories' => $categories,
            'types' => $types,
            'manufacturers' => $manufacturers,
            'parks' => $parks,
            '_hash' => $hash,
            '_new_url' => $new_url
        ]);
    }

    public function short($coaster) {
        try {
            $coaster = Coaster::select('slug')->with('park', function($query) {
                $query->select('short');
            })->where('id', $coaster)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return abort(404);
        }

        if($tab !== null) {
            $hash = "#{$tab}";
        } else {
            $hash = null;
        }

        return $this->view($coaster->park->short, $coaster->slug, $hash, route('coasters.coaster', ['park' => $coaster->park->short, 'coaster' => $coaster->slug]));
    }

    public function update(Request $request) {
        $id = $request->input('coaster');

        /* This permissions mess...
        If they 'Can manage coasters'.
            - Yes: continue
            - No: check if they can represent the park this coaster belongs to and we know the park ID.
                - Yes: continue
                - No: quit
        */

        if(!Auth::user()->can('Can manage coasters')) {
            if ($id !== null && Auth::user()->can('Can represent park')) {
                try {
                    $coaster = Coaster::where('id', $id)->with(['park' => function($query) {
                        $query->select('id');
                    }])->firstOrFail();
                } catch (ModelNotFoundException $e) {
                    return abort(404);
                }

                if (!Auth::user()->parks->contains('id', $coaster->park->id)) {
                    return abort(404);
                }
            } else {
                return abort(404);
            }
        }

        // Deleting a record. So sad.
        if($id !== null && $request->input('delete') == "true") {
            try {
                $park = Coaster::where('id', $id)->firstOrFail();
            } catch (ModelNotFoundException $e) {
                return abort(404);
            }

            $coaster->delete();

            return redirect(route('home'))->withSuccess("We're sorry to see it go.");
        }

        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required',
            'park' => 'required|numeric',
            'manufacturer' => 'required|numeric',
            'type' => 'required',
            'categories' => 'array',
            'rcdb_id' => 'nullable|numeric',
            'copyright' => 'nullable',
        ]);

        if($validation->fails()) {
            if($id !== null) {
                return redirect(route('coasters.coaster.id', ['coaster' => $id]))->withInput()->withErrors($validation);
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

        $coaster = Coaster::updateOrCreate([
            'id' => $id,
        ],[
            'name' => $request->input('name'),
            'slug' => $request->input('slug'),
            'city' => $request->input('city'),
            'manufacturer_id' => $request->input('manufacturer'),
            'park_id' => $request->input('park'),
            'type_id' => $request->input('type'),
            'rcdb_id' => $request->input('rcdb_id'),
            'img_url' => $img_url,
            'copyright' => $request->input('copyright'),
        ]);

        $coaster->categories()->sync($request->input('categories'));

        Cache::forget($coaster->park->short.':'.$coaster->slug);

        return redirect(route('coasters.coaster.id', ['coaster' => $coaster->id, 'tab' => 'edit']))->withSuccess("We've made some changes!");
    }

    public function new() {
        $categories = Cache::remember('all_categories', 120, function() {
            return Category::select('id', 'name')->get();
        });
        $types = Cache::remember('all_types', 120, function() {
            return Type::select('id', 'name')->get();
        });
        $manufacturers = Cache::remember('all_manu', 120, function() {
            return Manufacturer::select('id', 'name')->get();
        });
        $parks = Cache::remember('all_parks', 120, function() {
            return Park::select('id', 'name')->get();
        });

        return view('coasters.coaster.new', [
            'categories' => $categories,
            'types' => $types,
            'manufacturers' => $manufacturers,
            'parks' => $parks,
        ]);
    }
}
