<?php

namespace ChaseH\Http\Controllers\Ads;

use ChaseH\Models\Ads\Ad;
use ChaseH\Models\Ads\Campaign;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use ChaseH\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AdController extends Controller
{
    public function new() {
        $campaigns = Campaign::select('name', 'id')->whereHas('admins', function($query) {
            $query->where('user_id', Auth::id());
        })->get();

        return view('ads.ads.edit', [
            'ad' => null,
            'campaigns' => $campaigns,
        ]);
    }

    public function edit($ad) {
        $campaigns = Campaign::select('name', 'id')->whereHas('admins', function($query) {
            $query->where('user_id', Auth::id());
        })->get();

        try {
            $ad = Ad::where('id', $ad)->whereIn('campaign_id', $campaigns->pluck('id'))->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return abort(404);
        }

        return view('ads.ads.edit', [
            'ad' => $ad,
            'campaigns' => $campaigns,
        ]);
    }

    public function save(Request $request) {
        $v = Validator::make($request->all(), [
            'name' => 'string|required',
            'img_alt' => 'string|required',
            'img_href' => 'string|required',
            'sponsor' => 'string|nullable',
            'sponsor_href' => 'string|url',
            'image' => [
                'image',
                Rule::dimensions()->maxHeight(300),
            ],
            'campaign' => 'integer|required',
        ], [
            'image.dimensions' => "Your image must be less than 300px tall. It won't be shown any larger than that."
        ]);

        $v->sometimes('img_url', 'string|url', function($input) {
            return !$input->hasFile('image');
        });

        $v->validate(); // Run validation, redirect if needed

        $campaign = Campaign::select('id')->where('id', $request->campaign)->whereHas('admins', function($query) {
            $query->where('user_id', Auth::id());
        })->first();

        if($campaign == null) {
            return back()->withError("That' campaign isn't yours. We can't make an ad in it.");
        }

        // Find the right url to save. Either S3 or a seperate one.
        if($request->hasFile('image')) {
            $path = $request->image->store('sponsor-images', 's3');
            Storage::disk('s3')->setVisibility($path, 'public');

            $img_url = config('app.img')."/".$path;
        } else {
            $img_url = $request->img_url;
        }

        $ad = Ad::updateOrCreate([
            'id' => $request->ad ?? 0,
        ],[
            'name' => $request->name,
            'img_alt' => $request->img_alt,
            'img_href' => $request->img_href,
            'img_url' => $img_url,
            'sponsor' => $request->sponsor,
            'sponsor_href' => $request->sponsor_href,
            'campaign_id' => $request->campaign,
        ]);

        return redirect(route('ads.ad', ['ad' => $ad->id]))->withSuccess("We did it! Yay!");
    }

    public function delete(Request $request) {
        try {
            $ad = Ad::whereHas('campaign', function($query) {
                $query->whereHas('admins', function($q) {
                    $q->where('user_id', Auth::id());
                });
            })->where('id', $request->ad)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return abort(404);
        }

        $ad->delete();

        return redirect(route('ads.campaign', ['campaign' => $ad->campaign_id]))->withSuccess("It's gone now.");
    }
}
