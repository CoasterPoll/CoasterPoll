<?php

namespace ChaseH\Http\Controllers\Coasters;

use ChaseH\Http\Controllers\Controller;
use ChaseH\Jobs\UpdateRanking;
use ChaseH\Models\Coasters\Category;
use ChaseH\Models\Coasters\Coaster;
use ChaseH\Models\Coasters\Manufacturer;
use ChaseH\Models\Coasters\Park;
use ChaseH\Models\Coasters\Rank;
use ChaseH\Models\Coasters\Type;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class MainController extends Controller
{
    public function search() {
        return view('coasters.search');
    }

    public function display(Request $request) {
        if($request->get('limit') == "yes") {
            $coasters = Coaster::with(['park' => function($query) {
                $query->select('id', 'name', 'city', 'short');
            }, 'manufacturer' => function($query) {
                $query->select('id', 'name', 'abbreviation');
            }, 'type']);

            if($request->get('park')) {
                $coasters->where('park_id', $request->get('park'));
            }
            if($request->get('manufacturer')) {
                $coasters->where('manufacturer_id', $request->get('manufacturer'));
            }
            if($request->get('type')) {
                $coasters->where('type_id', $request->get('type'));
            }
            if($request->get('category')) {
                $coasters->whereHas('categories', function($query) use ($request) {
                    return $query->where('category_id', $request->get('category'));
                });
            }

            $coasters = $coasters->get();

            if($request->get('sort') == "coaster" && $request->get('direction') == "asc") {
                $coasters = $coasters->sortBy('name');
            }
            if($request->get('sort') == "coaster" && $request->get('direction') == "desc") {
                $coasters = $coasters->sortByDesc('name');
            }
            if($request->get('sort') == "park" && $request->get('direction') == "asc") {
                $coasters = $coasters->sortBy('park.name');
            }
            if($request->get('sort') == "park" && $request->get('direction') == "desc") {
                $coasters = $coasters->sortByDesc('park.name');
            }
            if($request->get('sort') == "manufacturer" && $request->get('direction') == "asc") {
                $coasters = $coasters->sortBy('manufacturer.name');
            }
            if($request->get('sort') == "manufacturer" && $request->get('direction') == "desc") {
                $coasters = $coasters->sortByDesc('manufacturer.name');
            }

            //dd($coasters->take(25));

            $coasters = new LengthAwarePaginator($coasters->forPage($request->get('page', 1), 25), $coasters->count(), 25);
            $coasters->setPath("");
        } else {
            $coasters = Cache::remember('coasters_list_pg:'.$request->input('page', 1), 30, function() {
                return Coaster::with(['park' => function($query) {
                    $query->select('id', 'name', 'city', 'short');
                }, 'manufacturer' => function($query) {
                    $query->select('id', 'name', 'abbreviation');
                }, 'type'])->orderBy('name', 'ASC')->paginate(25);
            });
        }

        $manufacturers = Cache::remember('all_manufacturers', 120, function() {
            return Manufacturer::orderBy('name', 'ASC')->get();
        });

        $parks = Cache::remember('all_parks', 120, function() {
            return Park::orderBy('name', 'ASC')->get();
        });

        $categories = Cache::remember('all_categories', 120, function() {
            return Category::orderBy('name', 'ASC')->get();
        });

        $types = Cache::remember('all_types', 120, function() {
            return Type::get();
        });

        return view('coasters.display', [
            'coasters' => $coasters,
            'types' => $types,
            'manufacturers' => $manufacturers,
            'parks' => $parks,
            'categories' => $categories,
            'request' => $request,
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

    public function rank($method = "auto", Request $request) {
        if($method == "auto") {
            $method = Auth::user()->getPreference('rank_form') ?? "dragdrop";
        }

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

        $complete = (array_sum($ranked->pluck('ballot_complete')->all()) > 0);

        if($method == "spreadsheet") {
            Auth::user()->setPreference(['rank_form' => 'spreadsheet']);

            $all = $ranked->merge($unranked);

            switch($request->get('sort')) {
                case "coaster":
                    $sorted = $all->sort(function($a, $b) {
                        return $a->getName() > $b->getName();
                    });
                    break;
                case "manufacturer":
                    $sorted = $all->sort(function($a, $b) {
                        return $a->getManufacturerName() > $b->getManufacturerName();
                    });
                    break;
                case "park":
                    $sorted = $all->sort(function($a, $b) {
                        return $a->getParkName() > $b->getParkName();
                    });
                    break;
                default:
                    $sorted = $all->sort(function($a, $b) {
                        return $a->getRank() > $b->getRank();
                    });
                    break;
            }

            return view('coasters.spreadsheet_rank', [
                'all' => $sorted,
                'complete' => $complete,
            ]);
        }

        Auth::user()->setPreference(['rank_form' => 'dragdrop']);

        return view('coasters.drag_rank', [
            'ranked' => $ranked->sortBy('rank'),
            'unranked' => $unranked,
            'complete' => $complete,
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

        Cache::forget('ranked:'.Auth::user()->id);
        Cache::forget('unranked:'.Auth::user()->id);

        return response()->json([
            'message' => "Done!",
        ]);
    }

    public function spreadsheetRank(Request $request) {
        $coasters = collect($request->get('coasters'))->map(function($item, $key) {
            return [
                'coaster' => $key,
                'rank' => $item
            ];
        });

        $user_id = Auth::id();

        $updates = $coasters->where('rank', '!=', null);

        foreach($updates as $update) {
            Rank::updateOrCreate([
                'user_id' => $user_id,
                'coaster_id' => $update['coaster'],
            ], [
                'rank' => $update['rank']
            ]);
        }

        Cache::forget('ranked:'.$user_id);
        Cache::forget('unranked:'.$user_id);

        return back()->withSuccess("Updated your rankings.");
    }

    public function completeBallot(Request $request) {
        Rank::where('user_id', Auth::id())->update([
            'ballot_complete' => 1,
        ]);

        Cache::forget('unranked:'.Auth::user()->id);
        Cache::forget('ranked:'.Auth::user()->id);

        return back()->withSuccess("Thanks! We'll be completing the results soon!");
    }

    public function incompleteBallot(Request $request) {
        Rank::where('user_id', Auth::id())->update([
            'ballot_complete' => 0,
        ]);

        Cache::forget('unranked:'.Auth::user()->id);
        Cache::forget('ranked:'.Auth::user()->id);

        return back()->withSuccess("Oh No! We'll be completing the results soon, you don't want to miss out.");
    }
}
