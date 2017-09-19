<?php

namespace ChaseH\Http\Controllers;

use ChaseH\Models\Analytics\Demographic;
use ChaseH\Models\Analytics\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller {
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Storage::disk('local')->exists('internal/homepage.txt')) {
            $content = Storage::disk('local')->get('internal/homepage.txt');
        } else {
            $content = "";
        }

        return view('home', [
            'content' => $content,
        ]);
    }

    public function edit() {
        if(Storage::disk('local')->exists('internal/homepage.txt')) {
            $current = Storage::disk('local')->get('internal/homepage.txt');
        } else {
            $current = "";
        }

        return view('admin.general.homepage', [
            'content' => $current,
        ]);
    }

    public function save(Request $request) {
        Storage::disk('local')->put('internal/homepage.txt', $request->get('content'));

        return back()->withSuccess("We've updated the homepage!");
    }
}
