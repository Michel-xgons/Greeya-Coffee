<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class PemesananController extends Controller
{
    /**
     * Tampilkan halaman data pemesan
     */
    public function index()
    {
        return view('frontend.Menu.Pemesanan');
    }

}
