<?php

namespace ChaseH\Http\Controllers;

use ChaseH\Models\Analytics\Demographic;
use ChaseH\Models\Analytics\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller {

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }
}
