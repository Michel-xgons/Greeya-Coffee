<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class CheckOutController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        // Proteksi cart kosong
        if (empty($cart)) {
            return redirect()->route('Branda')
                ->with('error', 'Keranjang masih kosong.');
        }


        $total = 0;
        foreach ($cart as $item) {
            $total += $item['harga'] * $item['qty'];
        }

        return view('frontend.Menu.checkout', compact('cart', 'total'));
    }


    public function get()
    {
        return response()->json(session('cart', []));
    }

    public function update(Request $request)
    {
        $request->validate([
            'row_id' => 'required',
            'change' => 'required|integer'
        ]);

        $cart = session()->get('cart', []);
        $row_id = $request->row_id;
        $change = (int) $request->change;

        if (!isset($cart[$row_id])) {
            return response()->json([
                'success' => false,
                'message' => 'Item tidak ditemukan'
            ]);
        }

        $cart[$row_id]['qty'] += $change;

        // Minimal qty
        if ($cart[$row_id]['qty'] < 1) {
            unset($cart[$row_id]);
        }

        // Maksimal qty
        if (isset($cart[$row_id]) && $cart[$row_id]['qty'] > 100) {
            $cart[$row_id]['qty'] = 100;
        }

        session()->put('cart', $cart);

        $grandTotal = collect($cart)->sum(function ($item) {
            return $item['harga'] * $item['qty'];
        });

        return response()->json([
            'success' => true,
            'total' => $grandTotal,
            'cart' => $cart
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

        return response()->json([
            'success' => true,
            'cart' => $cart
        ]);
    }

    public function process()
    {
        // Cek cart kosong
        if (empty(session('cart'))) {
            return redirect()->route('Branda')
                ->with('error', 'Keranjang kosong');
        }

        // Cek customer
        if (!session()->has('customer')) {
            return redirect()->route('Pemesanan')
                ->with('error', 'Silakan isi data pemesan');
        }

        return redirect()->route('payment');
    }
}
