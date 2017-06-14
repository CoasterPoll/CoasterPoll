<?php

namespace ChaseH\Http\Controllers\Sharing;

use ChaseH\Helpers\CPID;
use ChaseH\Models\Sharing\Link;
use Illuminate\Http\Request;
use ChaseH\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LinkController extends Controller
{
    public function index() {
        $links = Link::whereActive()->orderBy('created_at', 'DESC')->get();

        return view('sharing.index', [
            'links' => $links,
        ]);
    }

    public function view($link, $slug = null) {
        $model = Link::whereActive()->where('id', base_convert($link, 32, 10))->with('linkable')->first();

        return view('sharing.link', [
            'link' => $model,
        ]);
    }

    public function submit($what = null) {
        $on = new CPID($what);

        return view('sharing.submit', [
            'on' => $on,
            'link' => null,
        ]);
    }

    public function create(Request $request) {
        $v = Validator::make($request->all(), [
            'title' => 'required',
            'link' => 'required_without:body',
            'body' => 'required_without:link',
        ]);

        $v->sometimes('link', 'url', function($input) {
            return $input->link !== null;
        });

        $v->sometimes('body', 'max:65535', function($input) {
            return $input->body !== null;
        });

        $v->validate();

        $link = Link::create([
            'title' => $request->title,
            'link' => $request->link,
            'slug' => str_slug($request->title),
            'body' => e($request->body),
            'posted_by' => Auth::id()
        ]);

        if($request->on !== null) {
            $on = new CPID($request->on);

            $on->thing->links()->save($link);
        }

        return redirect($link->getLink());
    }
}