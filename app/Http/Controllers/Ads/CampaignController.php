<?php

namespace ChaseH\Http\Controllers\Ads;

use Carbon\Carbon;
use ChaseH\Models\Ads\Campaign;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use ChaseH\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CampaignController extends Controller
{
    public function manage() {
        $campaigns = Campaign::whereHas('admins', function($query) {
            $query->where('user_id', Auth::id());
        })->get();

        return view('ads.campaigns', [
            'campaigns' => $campaigns,
        ]);
    }

    public function view($campaign, Request $request) {
        try {
            $campaign = Campaign::where('id', $campaign)->whereHas('admins', function($query) {
                $query->where('user_id', Auth::id());
            })->with('ads', 'admins')->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return abort(404);
        }

        //dd($campaign->progressAsPercentage());

        return view('ads.campaigns.main', [
            'campaign' => $campaign,
            'view' => $request->cookie('preferredCampaignView', 'list'),
        ]);
    }

    public function new() {
        return view('ads.campaigns.campaign', ['campaign' => null]);
    }

    public function edit($campaign = 0) {
        $campaign = Campaign::where('id', $campaign)->whereHas('admins', function($query) {
            $query->where('user_id', Auth::id());
        })->first();

        return view('ads.campaigns.campaign', [
            'campaign' => $campaign,
        ]);
    }

    public function save(Request $request) {
        $this->validate($request, [
            'name' => 'required|string|min:3',
            'start_at' => 'required|string',
            'end_at' => 'required|string',
        ]);

        $campaign = Campaign::updateOrCreate([
            'id' => $request->campaign ?? 0,
        ],[
            'name' => $request->name,
            'start_at' => Carbon::parse($request->start_at),
            'end_at' => Carbon::parse($request->end_at),
            'cost' => 0,
            'budget' => 0,
            'paid' => 0
        ]);

        $campaign->admins()->attach(Auth::user());

        return redirect(route('ads.campaign', ['campaign' => $campaign->id]))->withSuccess("Successfully saved {$campaign->name}.");
    }

    public function delete(Request $request) {
        try {
            $campaign = Campaign::where('id', $request->campaign)->whereHas('admins', function($query) {
                $query->where('user_id', Auth::id());
            })->with('ads')->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return abort(404);
        }

        $campaign->delete();

        return redirect(route('ads'))->withSuccess("She's gone now.");
    }

    public function switchToPreview($campaign) {
        $campaign = Campaign::where('id', $campaign)->whereHas('admins', function($query) {
            $query->where('user_id', Auth::id());
        })->with('ads')->first();

        return response()->view('ads.campaigns.preview', [
            'campaign' => $campaign,
        ])->withCookie("preferredCampaignView", "preview", 60*24*365);
    }

    public function switchToList($campaign) {
        $campaign = Campaign::where('id', $campaign)->whereHas('admins', function($query) {
            $query->where('user_id', Auth::id());
        })->with('ads')->first();

        return response()->view('ads.campaigns.list', [
            'campaign' => $campaign,
        ])->withCookie("preferredCampaignView", "list", 60*24*365);
    }
}
