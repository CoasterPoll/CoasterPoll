<?php

namespace ChaseH\Http\Controllers;

use Carbon\Carbon;
use ChaseH\Models\Coasters\Coaster;
use ChaseH\Models\Coasters\Manufacturer;
use ChaseH\Models\Coasters\Park;
use ChaseH\Models\Coasters\Rank;
use ChaseH\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard() {
        $counts = Cache::remember('admin_counts', 60, function() {
            return json_encode([
                'user_objects' => User::withTrashed()->count(),
                'user_active' => User::count(),
                'user_new' => User::where('updated_at', '>=', Carbon::now()->subWeeks(2))->count(),
                'coasters' => Coaster::count(),
                'manufacturers' => Manufacturer::count(),
                'parks' => Park::count(),
                'ridden' => DB::table('coaster_user')->count(),
                'ranked' => Rank::count(),
                'completed' => round((Rank::where('ballot_complete', 1)->count() / Rank::where('ballot_complete', 0)->count()) * 100),
            ]);
        });

        $searches = Cache::remember('search_counts', 60, function() {
            $guzzle = new Client([
                'headers' => [
                    'X-Algolia-Application-Id' => config('scout.algolia.id'),
                    'X-Algolia-API-Key' => config('scout.algolia.monitoring'),
                ]
            ]);

            // Search Operations
            $response = $guzzle->request("GET", "https://status.algolia.com/1/usage/*/period/month");

            if($response->getStatusCode() == 200) {
                $results = json_decode($response->getBody());
                $searches = 0;
                foreach($results->total_search_operations as $operation) {
                    $searches = $operation->v + $searches;
                }

                $operations = 0;
                foreach($results->total_operations as $operation) {
                    $operations = $operation->v + $operations;
                }

                $records = 0;
                foreach($results->records as $operation) {
                    $records = $operation->v;
                }
            } else {
                $searches = "n/a";
                $operations = "n/a";
                $records = "n/a";
            }

            return [
                'searches' => $searches,
                'operations' => $operations,
                'records' => $records,
            ];
        });

        return view('console', [
            'counts' => json_decode($counts),
            'searches' => $searches,
        ]);
    }

    public function search(Request $request) {
        if($request->input('q') == null) {
            return view('admin.search');
        }

        // Search for users
        $users = User::look($request->input('q'));

        // Gather results
        $results = collect($users);

        return view('admin.search', [
            'results' => $results,
            'query' => $request->input('q'),
        ]);
    }
}
