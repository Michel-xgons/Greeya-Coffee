<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;

class BrandaController extends Controller
{
    public function index()
    {
        $menuMakanan = Menu::where('id_kategori', 1)->get();
        $menuMinuman = Menu::where('id_kategori', 2)->get();
        return view('frontend.Menu.Branda', compact('menuMakanan', 'menuMinuman')); 
    }

    public function show($id)
    {
        $menu = Menu::findOrFail($id);
        return view('frontend.Menu.detailmenu', compact('menu'));
    }
}
