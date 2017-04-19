<?php

namespace ChaseH\Http\Controllers\Coasters;

use ChaseH\Models\Coasters\Coaster;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use ChaseH\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

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

        return view('coasters.coaster', [
            'coaster' => $coaster,
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

        return redirect(route('coasters.coaster', ['park' => $coaster->park->short, 'coaster' => $coaster->slug]));
    }
}
