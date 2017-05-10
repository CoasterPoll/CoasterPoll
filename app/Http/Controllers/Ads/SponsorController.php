<?php

namespace ChaseH\Http\Controllers\Ads;

use Carbon\Carbon;
use ChaseH\Models\Ads\Campaign;
use Illuminate\Http\Request;
use ChaseH\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SponsorController extends Controller
{
    public function dashboard() {
        if(Auth::user()->can('Can post ads') || Auth::user()->can('Can view ad details')) {
            $campaigns = Campaign::where('end_at', '>=', Carbon::now())->whereHas('admins', function($query) {
                $query->where('user_id', Auth::id());
            })->get();

            return view('ads.dashboard', [
                'campaigns' => $campaigns
            ]);
        }

        return view('ads.about');
    }

    public function join() {
        if(Auth::user()->cannot('Can sponsor')) {
            return abort(403);
        }

        Auth::user()->givePermissionTo('Can post ads', 'Can view ad details');

        return back()->withSuccess("Welcome! Feel free to look around.");
    }
}
