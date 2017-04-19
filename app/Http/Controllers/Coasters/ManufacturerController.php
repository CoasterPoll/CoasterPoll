<?php

namespace ChaseH\Http\Controllers\Coasters;

use ChaseH\Models\Coasters\Manufacturer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use ChaseH\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class ManufacturerController extends Controller
{
    public function view($manufacturer) {
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
        ]);
    }

    public function short($manufacturer) {
        try {
            $manufacturer = Manufacturer::select('abbreviation')->where('id', $manufacturer)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return abort(404);
        }

        return redirect(route('coasters.manufacturer', ['manufacturer' => $manufacturer->abbreviation]));
    }
}
