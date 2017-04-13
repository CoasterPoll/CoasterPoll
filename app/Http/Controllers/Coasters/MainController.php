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
                $query->select('id', 'name', 'city');
            }, 'manufacturer' => function($query) {
                $query->select('id', 'name');
            }, 'type'])->paginate(25);
        });

        return view('coasters.display', [
            'coasters' => $coasters,
        ]);
    }
}
