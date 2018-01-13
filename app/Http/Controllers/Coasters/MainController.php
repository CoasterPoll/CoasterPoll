<?php

namespace ChaseH\Http\Controllers\Coasters;

use ChaseH\Http\Controllers\Controller;
use ChaseH\Jobs\UpdateRanking;
use ChaseH\Models\Coasters\Coaster;
use ChaseH\Models\Coasters\Rank;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            }, 'type'])->orderBy('name', 'ASC')->paginate(25);
        });

        return view('coasters.display', [
            'coasters' => $coasters,
        ]);
    }

    public function ridden() {
        return view('coasters.ridden', [
            'coasters' => Auth::user()->ridden->load('park', 'manufacturer'),
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
            Auth::user()->ranked()->where('coaster_id', $coaster->id)->delete();
            $mark = 'false';
        }

        // Clear cache so it shows up instantly
        Cache::forget('ridden:'.Auth::user()->id);
        Cache::forget('unranked:'.Auth::user()->id);
        Cache::forget('ranked:'.Auth::user()->id);

        return response()->json([
            'message' => "Thanks for telling us!",
            'mark' => $mark,
        ]);
    }

    public function rank() {
        $ranked = Cache::remember('ranked:'.Auth::user()->id, 60, function() {
            $ranked = Auth::user()->ranked;
            $ranked->load('coaster', 'coaster.manufacturer', 'coaster.park');
            return $ranked;
        });

        $unranked = Cache::remember('unranked:'.Auth::user()->id, 60, function() use ($ranked) {
            $unranked = Coaster::whereIn('id', Auth::user()->ridden->pluck('id', 'id'))
                ->whereNotIn('id', $ranked->pluck('coaster_id', 'coaster_id')->toArray())
                ->with('manufacturer', 'park')->get();
            return $unranked;
        });

        return view('coasters.rank', [
            'ranked' => $ranked->sortBy('rank'),
            'unranked' => $unranked,
        ]);
    }

    public function updateRank(Request $request) {
        $input = $request->input('all');
        $user_id = Auth::user()->id;

        foreach($input as $request) {
            Rank::where('coaster_id', $request['coaster'])
                ->where('user_id', $user_id)
                ->update(['rank' => $request['rank']]);
        }

        Cache::forget('ranked:'.$user_id);
        Cache::forget('unranked:'.$user_id);

        return response()->json([
            'message' => "Saved your new order! It'll be live soon.",
        ]);
    }

    public function newRank(Request $request) {
        $this->validate($request, [
            'coaster' => 'required',
            'rank' => 'required|numeric'
        ]);

        if(!Auth::user()->ridden->contains('coaster_id', $request->input('coaaster'))) {
            abort(400);
        }

        Rank::create([
            'coaster_id' => $request->input('coaster'),
            'user_id' => Auth::user()->id,
            'rank' => $request->input('rank')
        ]);

        return response()->json([
            'message' => "Done!",
        ]);
    }
}
