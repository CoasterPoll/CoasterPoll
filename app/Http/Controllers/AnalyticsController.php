<?php

namespace ChaseH\Http\Controllers;

use ChaseH\Models\Analytics\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AnalyticsController extends Controller {
    public function view(Request $request) {
        if(env('TRACK_VIEWS', true)) {
            if (Auth::check()) {
                $user_id = Auth::id();
            } else {
                $user_id = null;
            }

            $query = array();
            parse_str($request->input('query'), $query);

            View::create([
                'page' => $request->input('page'),
                'time' => $request->input('time'),
                'user_id' => $user_id,
                'query' => json_encode($query),
                'hash' => $request->input('hash'),
                'referrer' => $request->input('referrer'),
                'session' => Session::getId(),
            ]);
        }

        return response()->json([
            'success' => true,
        ]);
    }
}