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

    public function cart_add(Request $request)
{
    dd($request->all());    
    $cart = session('cart', []);

    $qty = (int) $request->qty;

    $found = false;

    foreach ($cart as &$item) {
        if ($item['id'] == $request->id_menu) {
            $item['qty'] += $qty;
            $found = true;
            break;
        }
    }

    if (!$found) {
        $cart[] = [
            'id' => $request->id_menu,
            'name' => $request->nama_menu,
            'price' => $request->harga,
            'qty' => $qty,
            'note' => '',
            'variant' => ''
        ];
    }

    dd($cart);
    
    session(['cart' => $cart]);

    return redirect()->with('success', 'Menu added to cart!');
}


    public function show($id)
    {
        $menu = Menu::findOrFail($id);
        return view('frontend.Menu.detailmenu', compact('menu'));
    }
}
