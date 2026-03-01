<?php

namespace App\Http\Controllers;

use App\Models\KategoriMenu;
use Illuminate\Http\Request;
use App\Models\Menu;

class BrandaController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['harga'] * $item['qty'];
        }

        $kategoris = KategoriMenu::with('menus')->get();
        

        return view(
            'frontend.Menu.Branda',
            [
                'kategoris'      => $kategoris
            ]
        );
    }

    public function cart_add(Request $request)
{
    $menu = Menu::findOrFail($request->id);

    $cart = session('cart', []);
    
    $qty     = (int) $request->qty;
    $varian = $request->varian;
    $note    = $request->note ?? '';

    // $id      = $request->id;
    // $nama    = $request->nama;
    // $harga   = $request->harga;
    

    // Buat row_id unik
    $row_id = md5($menu->id . '-' . $varian . '-' . $note);

    if (isset($cart[$row_id])) {

        $cart[$row_id]['qty'] += $qty;

    } else {

        $cart[$row_id] = [
            'row_id' => $row_id,
            'id'      => $menu->id,
            'nama'    => $menu->nama,
            'harga'   => $menu->harga,
            'gambar'  => $menu->gambar,
            'qty'     => $qty,
            'varian' => $varian,
            'note'    => $note,
        ];
    }

    session(['cart' => $cart]);

    return redirect()->back()->with('success', 'Menu added to cart!');
}

    public function show($id, Request $request)
    {
        $menu = Menu::findOrFail($id);

        $from = $request->query('from');

        return view('frontend.Menu.DetailMenu', compact('menu', 'from'));
    }
}
