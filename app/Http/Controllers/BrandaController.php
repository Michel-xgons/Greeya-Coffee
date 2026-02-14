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

        $menu = \App\Models\Menu::find($request->id);

        if (!$menu) {
            return back()->with('error', 'Menu tidak ditemukan');
        }

        $cart = session()->get('cart', []);

        $rowId = md5($menu->id_menu . '_' . $request->variant);

        $found = false;

        foreach ($cart as &$item) {
            if (isset($item['row_id']) && $item['row_id'] === $rowId) {
                $item['qty'] += 1;
                $found = true;
                break;
            }
        }

        if (!$found) {
            $cart[] = [
                'row_id' => $rowId,
                'id' => $menu->id_menu,
                'name' => $menu->nama_menu,
                'price' => $menu->harga,
                'qty' => 1,
                'note' => $request->note,
                'variant' => $request->variant ?? 'ICE'
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('checkout');
    }




    //     public function cart_add(Request $request)
    // {
    //     $cart = session('cart', []);

    //     $rowId = md5($request->id_menu . '_' . $request->variant);

    //     $found = false;

    //     foreach ($cart as &$item) {
    //         if ($item['row_id'] == $rowId) {
    //             $item['qty'] += $request->qty;
    //             $found = true;
    //             break;
    //         }
    //     }

    //     if (!$found) {
    //         $cart[] = [
    //             'row_id'  => $rowId,
    //             'id'      => $request->id_menu,
    //             'name'    => $request->name,
    //             'price'   => $request->price,
    //             'qty'     => $request->qty,
    //             'variant' => $request->variant,
    //             'note'    => $request->note ?? ''
    //         ];
    //     }

    //     session(['cart' => $cart]);

    //     return redirect()->route('checkout');
    // }


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
    //             'id' => $request->id,
    //             'name' => $request->nama,
    //             'price' => $request->harga,
    //             'qty' => $qty,
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
