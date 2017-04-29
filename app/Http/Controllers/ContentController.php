<?php

namespace ChaseH\Http\Controllers;

use ChaseH\Models\Content\Link;
use ChaseH\Models\Content\Page;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;

class ContentController extends Controller
{
    public function page($page, Request $request) {
        if($pg = Cache::get('page:'.$page)) {
            $this->links();
            return view('content.page', ['page' => $pg]);
        }

        try {
            $pg = Page::where('url', $page)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return abort(404);
        }

        Cache::put('page:'.$page, $pg, 240);

        // Add sidebar links
        $this->links();

        return view('content.page', ['page' => $pg]);
    }

    private function links() {
        $links = Cache::remember('content-links', 120, function() {
            return Link::where('location', 'content')->orderBy('order', 'ASC')->get();
        });

        View::share('links', $links);
    }

    public function adminPages() {
        $pages = Page::paginate(25);

        return view('content.pages', ['pages' => $pages]);
    }

    public function adminPage($page = 0) {
        if($page == 0) {
            return view('content.write');
        }

        $page = Page::where('id', $page)->first();

        return view('content.write', [
            'page' => $page,
        ]);
    }

    public function savePage(Request $request) {
        $this->validate($request, [
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string',
            'url' => 'nullable|string',
            'body' => 'required',
        ]);

        if ($request->input('url') !== "" && $request->input('url') !== null) {
            $url = $request->input('url');
        } else {
            $url = $request->input('title');
        }

        $page = Page::updateOrCreate([
            'id' => $request->input('page'),
        ], [
            'title' => $request->input('title'),
            'subtitle' => $request->input('subtitle'),
            'url' => $url,
            'body' => $request->input('body'),
        ]);

        return redirect(route('content', ['page' => $page->url]))->withSuccess("Updated the page. Better proofread it again!");
    }

    public function adminLinks($link = 0) {
        return view('content.links', [
            'links' => Link::orderBy('order', 'ASC')->get(),
            'lnk' => Link::find($link),
        ]);
    }

    public function saveLink(Request $request) {
        $this->validate($request, [
            'text' => 'required|string',
            'href' => 'required|url'
        ]);

        Link::updateOrCreate([
            'text' => $request->input('text'),
        ], [
            'href' => $request->input('href'),
            'order' => $request->input('order'),
            'location' => $request->input('location'),
        ]);

        // Clear cache so things actually change.
        Cache::forget('content-links');
        Cache::forget('navbar-links');
        Cache::forget('footer-links');

        return back()->withSuccess("We did it!");
    }

    public function deleteLink(Request $request) {
        Link::find($request->input('link'))->delete();

        // Clear cache so things actually change.
        Cache::forget('content-links');
        Cache::forget('navbar-links');
        Cache::forget('footer-links');

        return back()->withSuccess("Bye!");
    }
}
