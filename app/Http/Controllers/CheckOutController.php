<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckOutController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('Branda')
                ->with('error', 'Keranjang masih kosong.');
        }

        $total = collect($cart)->sum(fn($item) => $item['harga'] * $item['qty']);

        return view('frontend.Menu.checkout', compact('cart', 'total'));
    }

    public function cart()
    {
        $cart = session('cart', []);

        $total = collect($cart)->sum(fn($item) => $item['harga'] * $item['qty']);

        $html = view('frontend.cart.items', compact('cart'))->render();

        return response()->json([
            'html' => $html,
            'total' => $total,
            'total_item' => collect($cart)->sum('qty')
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'row_id' => 'required',
            'action' => 'required|in:plus,minus'
        ]);

        $cart = session()->get('cart', []);
        $row_id = $request->row_id;

        if (!isset($cart[$row_id])) {
            return response()->json([
                'success' => false,
                'message' => 'Item tidak ditemukan'
            ]);
        }

        if ($request->action === 'plus') {
            $cart[$row_id]['qty'] += 1;
        }

        if ($request->action === 'minus') {
            $cart[$row_id]['qty'] -= 1;
        }


        if ($cart[$row_id]['qty'] < 1) {
            unset($cart[$row_id]);
        }

        if (isset($cart[$row_id]) && $cart[$row_id]['qty'] > 100) {
            $cart[$row_id]['qty'] = 100;
        }

        session()->put('cart', $cart);

        $total = collect($cart)->sum(fn($item) => $item['harga'] * $item['qty']);

        return response()->json([
            'success' => true,
            'html' => view('frontend.cart.items', compact('cart'))->render(),
            'total' => $total,
            'total_item' => collect($cart)->sum('qty')
        ]);
    }

    public function note(Request $request)
    {
        $request->validate([
            'row_id' => 'required',
            'note' => 'nullable|string|max:255'
        ]);

        $cart = session()->get('cart', []);
        $row_id = $request->row_id;

        if (!isset($cart[$row_id])) {
            return response()->json([
                'success' => false,
                'message' => 'Item tidak ditemukan'
            ], 404);
        }

        $note = trim($request->note);
        $cart[$row_id]['note'] = $note === '' ? null : $note;

        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'note' => $cart[$row_id]['note']
        ]);
    }

    public function remove(Request $request)
    {
        $cart = session()->get('cart', []);
        $row_id = $request->row_id;

        if (isset($cart[$row_id])) {
            unset($cart[$row_id]);
        }

        session()->put('cart', $cart);

        $total = collect($cart)->sum(fn($item) => $item['harga'] * $item['qty']);

        return response()->json([
            'success' => true,
            'html' => view('frontend.cart.items', compact('cart'))->render(),
            'total' => $total,
            'total_item' => collect($cart)->sum('qty')
        ]);
    }

    public function auto()
    {
        if (empty(session('cart'))) {
            return redirect()->route('Branda')
                ->with('error', 'Cart kosong');
        }

        if (!session()->has('customer_data')) {
            return redirect()->route('Pemesanan');
        }

        return redirect()->route('pemesanan.auto');
    }
}
