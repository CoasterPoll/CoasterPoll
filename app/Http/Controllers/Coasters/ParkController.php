<?php

namespace ChaseH\Http\Controllers\Coasters;

use ChaseH\Models\Coasters\Park;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use ChaseH\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class ParkController extends Controller
{
    public function view($park) {
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
        ]);
    }
}
