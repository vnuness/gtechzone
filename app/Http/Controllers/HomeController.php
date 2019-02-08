<?php

namespace App\Http\Controllers;


class HomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('dashboard.view');

        return view('home');
    }
}
