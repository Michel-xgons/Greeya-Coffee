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
            $total += $item['price'] * $item['qty'];
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
                'id' => $request->id,
                'name' => $request->nama,
                'price' => $request->harga,
                'qty' => $qty,
            ];
        }


        session(['cart' => $cart]);

        return redirect('/')->with('success', 'Menu added to cart!');
    }


    public function show($id, Request $request)
    {
        $menu = Menu::findOrFail($id);

        $from = $request->query('from');

        return view('frontend.Menu.DetailMenu', compact('menu', 'from'));
    }
}
