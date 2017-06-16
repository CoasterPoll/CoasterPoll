<?php

namespace ChaseH\Http\Controllers\Sharing;

use ChaseH\Helpers\CPID;
use ChaseH\Models\Sharing\Link;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use ChaseH\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class LinkController extends Controller
{
    public function index() {
        $links = Link::whereActive()->orderBy('created_at', 'DESC')->paginate(25);

        return view('sharing.index', [
            'links' => $links,
        ]);
    }

    public function view($link, $slug = null, Request $request) {
        $model = Link::whereActive()->where('id', base_convert($link, 32, 10))->with('linkable')->first();

        // Pagination!
        $page = $request->get('page', 1);
        $perPage = 10;

        $comments = $model->nestedComments();

        $comments = new LengthAwarePaginator(
            $comments,
            count($model->comments->where('parent_id', null)) ?? 0,
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        ) ?? collect();

        return view('sharing.link', [
            'link' => $model,
            'comments' => $comments,
        ]);
    }

    public function submit($what = null, Request $request) {
        if(!Auth::check()) {
            $request->session()->flash("info", "You'll need to login/register before you can submit a link.");
            return redirect(route("register"));
        }

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

    public function edit(Request $request) {
        try {
            $link = Link::where('id', $request->id)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return abort(404);
        }

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

        if(Gate::allows('Can edit links') || Auth::id() == $link->posted_by) {
            $link->update([
                'title' => $request->title,
                'link' => $request->link,
                'body' => e($request->body),
            ]);
        }

        return response()->json($link->toArray());
    }
}