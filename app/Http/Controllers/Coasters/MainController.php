<?php

namespace ChaseH\Http\Controllers\Coasters;

use ChaseH\Http\Controllers\Controller;
use ChaseH\Models\Coasters\Coaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MainController extends Controller
{
    public function search() {
        return view('coasters.search');
    }

    public function display(Request $request) {
        $coasters = Cache::remember('coasters_list_pg:'.$request->input('page', 1), 30, function() {
            return Coaster::with(['park' => function($query) {
                $query->select('id', 'name', 'city', 'short');
            }, 'manufacturer' => function($query) {
                $query->select('id', 'name', 'abbreviation');
            }, 'type'])->paginate(25);
        });

        return view('coasters.display', [
            'coasters' => $coasters,
        ]);
    }
    public function ride(Request $request) {
        try {
            $coaster = Coaster::where('id', $request->input('coaster'))->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return abort(404);
        }

        if($request->input('ridden') == "false") {
            Auth::user()->ridden()->attach($coaster->id);
            $mark = 'true';
        } else {
            Auth::user()->ridden()->detach($coaster->id);
            $mark = 'false';
        }

        // Clear cache so it shows up instantly
        Cache::forget('ridden:'.Auth::id());

        return response()->json([
            'message' => "Looks like it wasn't too bad...",
            'mark' => $mark,
        ]);
    }
}
