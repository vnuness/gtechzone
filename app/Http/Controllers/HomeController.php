<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;

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

    public function listProducts(Request $request)
    {
        if($request->os != '') {
            $query = Products::select('n_os', 'n_serie', 'aparelho', 'localizacao')
            ->where('n_os', 'like', $request->os)
            ->get();
        } else {
            $query = Products::select('n_os', 'n_serie', 'aparelho', 'localizacao')
            ->get();
        }

        return response()->json(['data' => $query]);
    }
}
