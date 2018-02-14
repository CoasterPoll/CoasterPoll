<?php

namespace ChaseH\Http\Controllers\Coasters;

use ChaseH\Jobs\Coasters\OverallRank;
use ChaseH\Models\Coasters\Category;
use ChaseH\Models\Coasters\Coaster;
use ChaseH\Models\Coasters\Manufacturer;
use ChaseH\Models\Coasters\Park;
use ChaseH\Models\Coasters\Result;
use ChaseH\Models\Coasters\ResultPage;
use ChaseH\Models\Coasters\Type;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use ChaseH\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ResultsController extends Controller
{
    public function manage(Request $request, $page = 0) {
        $categories = Cache::remember('all_categories', 120, function() {
            return Category::select('id', 'name')->orderBy('name', 'ASC')->get();
        });
        $types = Cache::remember('all_types', 120, function() {
            return Type::select('id', 'name')->orderBy('name', 'ASC')->get();
        });
        $manufacturers = Cache::remember('all_manu', 120, function() {
            return Manufacturer::select('id', 'name')->orderBy('name', 'ASC')->get();
        });
        $parks = Cache::remember('all_parks', 120, function() {
            return Park::select('id', 'name')->orderBy('name', 'ASC')->get();
        });

        $pages = ResultPage::get();

        $groups = Result::select('group')->distinct()->get();

        return view('coasters.results.manage', [
            'categories' => $categories,
            'types' => $types,
            'manufacturers' => $manufacturers,
            'parks' => $parks,
            'pages' => $pages,
            'groups' => $groups,
            'edit_page' => ResultPage::find($page),
        ]);
    }

    public function run(Request $request) {
        $this->validate($request, [
            'group' => 'required|string|max:255'
        ]);

        // Get a query builder instance started, that selects what we want.
        $coasters_query = Coaster::select('id')->with(['rankings' => function($q) {
            $q->select('user_id', 'coaster_id', "rank");
        }]);

        // Filter by category
        if($request->input('categories') !== null) {
            $coasters_query = $coasters_query->whereHas('categories', function($q) use ($request) {
                $q->whereIn('id', $request->input('categories'));
            });
        }

        // Filter by park
        if($request->input('park') !== null) {
            $coasters_query = $coasters_query->whereIn('park_id', $request->input('park'));
        }

        // Filter by manufacturer
        if($request->input('manufacturer') !== null) {
            $coasters_query = $coasters_query->whereIn('manufacturer_id', $request->input('manufacturer'));
        }

        // Filter by type
        if($request->input('type') !== null) {
            $coasters_query = $coasters_query->whereIn('type_id', $request->input('type'));
        }

        // Get and make sure that it actually worked.
        $coasters = $coasters_query->get();

        if($coasters->count() == 0) {
            return back()->withDanger("Whoops. Looks like there's not actually any coasters to rank.");
        }

        dispatch(new OverallRank($request->input('group'), Auth::user(), $coasters));


        // Return to the manage page.
        return back()->withSuccess("Cool. We've started processing that job. We'll let you know when it's done.");
    }

    public function savePage(Request $request) {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'url' => 'required|string',
            'group' => 'required',
            'description' => 'nullable|string',
        ]);

        $run_at = Result::select('created_at')->where('group', $request->input('group'))->orderBy('created_at', 'DESC')->first();

        $page = ResultPage::updateOrCreate([
            'id' => $request->input('page'),
        ], [
            'name' => $request->input('name'),
            'url' => $request->input('url'),
            'group' => $request->input('group'),
            'description' => $request->input('description', 0),
            'public' => $request->input('public', 0),
            'run_at' => $run_at->created_at,
        ]);

        if($request->input('default', "off") == "on") {
            $page->setDefault();
        }

        if(ResultPage::where('public', true)->where('default', true)->count() > 0) {
            Cache::forever('has-results', true);
        } else {
            Cache::forget('has-results');
        }

        return back()->withSuccess("We've saved that page.");
    }

    public function results($url = null) {
        // If we don't have a url to go to.
        if($url == null) {
            try {
                $page = ResultPage::where('default', true)->firstOrFail();
            } catch (ModelNotFoundException $e) {
                Cache::forget('has-results');
                return abort(404);
            }
        } else { // If we're looking at a specific url
            try {
                $page = ResultPage::where('url', $url)->firstOrFail();
            } catch (ModelNotFoundException $e) {
                Cache::forget('has-results');
                return abort(404);
            }
        }

        $results = Result::where('group', $page->group)->with('coaster', 'coaster.park', 'coaster.manufacturer')->orderBy('percentage', 'DESC')->paginate(50);

        return view('coasters.results', [
            'results' => $results,
            'page' => $page,
        ]);
    }

    public function deleteGroup(Request $request) {
        $this->validate($request, [
            'group' => 'string|required',
        ]);

        $query = Result::select('id')->where('group', $request->input('group'));

        $count = $query->count();

        $query->delete();

        return back()->withSuccess("We've removed {$count} result entries.");
    }

    public function deletePage(Request $request) {
        ResultPage::where('id', $request->input('page'))->delete();

        return back()->withSuccess("Deleted that page.");
    }
}
