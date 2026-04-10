<?php

namespace App\Http\Controllers;


use App\Models\Kategoris;
use App\Models\Menus;
use Illuminate\Http\Request;
use App\Models\Variant;

// use Illuminate\Support\Facades\Log;

class BrandaController extends Controller
{
    public function index()
    {
        $kategoris = Kategoris::with('menus.variants')->get();

        return view(
            'frontend.Menu.Branda',
            [
                'kategoris'      => $kategoris
            ]
        );
    }

    public function cart_add(Request $request)
{
    if (!session('nomor_meja')) {
        return response()->json([
            'success' => false,
            'message' => 'Nomor meja belum dipilih'
        ], 400);
    }

    $request->validate([
        'variant_id' => 'nullable|exists:variants,id',
        'menu_id' => 'nullable|exists:menus,id',
        'qty' => 'required|integer|min:1|max:10',
        'note' => 'nullable|string|max:255',
    ]);

    //  BEDAKAN VARIANT / NON VARIANT
    if ($request->variant_id) {
        $variant = Variant::with('menu')->findOrFail($request->variant_id);
        $menu = $variant->menu;

        $harga = $variant->harga;
        $namaVarian = $variant->nama_variant;
        $variant_id = $variant->id;

        $row_id = md5($variant->id . '-' . strtolower($request->note));

    } else {
        $menu = Menus::findOrFail($request->menu_id);

        $harga = $menu->harga;
        $namaVarian = null;
        $variant_id = null;

        $row_id = md5($menu->id . '-' . strtolower($request->note));
    }

    $cart = session('cart', []);

    $qty = max(1, (int) $request->qty);
    $note = trim($request->note ?? '');

    if (isset($cart[$row_id])) {
        $cart[$row_id]['qty'] = min(10, $cart[$row_id]['qty'] + $qty);
    } else {
        $cart[$row_id] = [
            'row_id' => $row_id,
            'variant_id' => $variant_id,
            'menu_id' => $menu->id,
            'nama' => $menu->nama_menu,
            'varian' => $namaVarian,
            'harga' => $harga,
            'gambar' => $menu->gambar,
            'qty' => $qty,
            'note' => $note,
        ];
    }

    session()->put('cart', $cart);

    $total = collect($cart)->sum(fn($item) => $item['harga'] * $item['qty']);

    return response()->json([
        'success' => true,
        'total_item' => collect($cart)->sum('qty'),
        'total' => $total,
        'html' => view('frontend.cart.items', compact('cart'))->render()
    ]);
}

public function cart_update(Request $request)
{
    $cart = session('cart', []);
    $id = $request->row_id;

    if (isset($cart[$id])) {

        if ($request->action === 'plus') {
            $cart[$id]['qty'] = min(10, $cart[$id]['qty'] + 1);
        }

        if ($request->action === 'minus') {
            $cart[$id]['qty'] = max(1, $cart[$id]['qty'] - 1);
        }

        session()->put('cart', $cart);
    }

    $total = collect($cart)->sum(fn($item) => $item['harga'] * $item['qty']);

    return response()->json([
        'html' => view('frontend.cart.items', compact('cart'))->render(),
        'total' => $total,
        'total_item' => collect($cart)->sum('qty')
    ]);
}

public function cart_delete(Request $request)
{
    $cart = session('cart', []);
    unset($cart[$request->row_id]);

    session()->put('cart', $cart);

    $total = collect($cart)->sum(fn($item) => $item['harga'] * $item['qty']);

    return response()->json([
        'html' => view('frontend.cart.items', compact('cart'))->render(),
        'total' => $total,
        'total_item' => collect($cart)->sum('qty')
    ]);
}

    public function show($id_menu, Request $request)
    {
        $menu = Menus::findOrFail($id_menu);

        $from = $request->query('from');
        $qty = max(1, (int) $request->query('qty', 1));

        return view('frontend.Menu.DetailMenu', compact('menu', 'from', 'qty'));
    }

    public function search(Request $request)
    {
        $keyword = $request->keyword;

        $menus = Menus::where('nama', 'like', "%{$keyword}%")->get();

        return response()->json($menus);
    }
}
