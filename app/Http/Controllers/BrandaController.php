<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BrandaController extends Controller
{
    public function index()
    {
        return view('frontend.Menu.Branda');
    }
}
