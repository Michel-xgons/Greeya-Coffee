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
    $cart = session('cart', []);

    $id      = $request->id;
    $nama    = $request->nama;
    $harga   = $request->harga;
    $qty     = (int) $request->qty;
    $variant = $request->variant ?? 'DEFAULT';
    $note    = $request->note ?? '';

    // Buat row_id unik
    $row_id = md5($id . '-' . $variant . '-' . $note);

    if (isset($cart[$row_id])) {

        $cart[$row_id]['qty'] += $qty;

    } else {

        $cart[$row_id] = [
            'row_id' => $row_id,
            'id'      => $id,
            'nama'    => $nama,
            'harga'   => $harga,
            'qty'     => $qty,
            'variant' => $variant,
            'note'    => $note,
        ];
    }

    session(['cart' => $cart]);

    return redirect()->back()->with('success', 'Menu added to cart!');
}


//cart add lama
    // public function cart_add(Request $request)
    // {
    //     $cart = session('cart', []);
        

    //     $qty = (int) $request->qty;

    //     $found = false;

    //     foreach ($cart as &$item) {
    //         if ($item['id'] == $request->id_menu) {
    //             $item['qty'] += $qty;
    //             $found = true;
    //             break;
    //         }
    //     }

    //     if (!$found) {
    //         $cart[] = [
    //             'id' => $request->id_menu,
    //             'name' => $request->nama,
    //             'price' => $request->harga,
    //             'qty' => $qty,
    //             'note' => '',
    //         ];
    //     }


    //     session(['cart' => $cart]);

    //     return redirect('/')->with('success', 'Menu added to cart!');
    // }


    public function show($id, Request $request)
    {
        $menu = Menu::findOrFail($id);

        $from = $request->query('from');

        return view('frontend.Menu.DetailMenu', compact('menu', 'from'));
    }
}
