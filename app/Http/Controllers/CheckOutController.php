<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckOutController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['qty'];
        }

        return view('frontend.Menu.checkout', compact('cart', 'total'));
    }


    public function get()
    {
        return response()->json(session('cart', []));
    }

    public function update(Request $request)
    {
        $cart = session('cart', []);

        $found = false;

        foreach ($cart as &$item) {
            if ($item['id'] == $request->id) {
                $item['qty'] += $request->change;
                if ($item['qty'] <= 0) {
                    $item['qty'] = 0;
                }
                $found = true;
                break;
            }
        }

        if (!$found && $request->change > 0) {
            $cart[] = [
                'id' => $request->id,
                'name' => $request->name,
                'price' => $request->price,
                'qty' => 1,
                'note' => '',
                'variant' => ''
            ];
        }

        $cart = array_filter($cart, fn($i) => $i['qty'] > 0);

        session(['cart' => array_values($cart)]);

        return response()->json(session('cart'));
    }

    public function note(Request $request)
    {
        $cart = session('cart', []);

        foreach ($cart as &$item) {
            if ($item['id'] == $request->id) {
                $item['note'] = $request->note;
                break;
            }
        }

        session(['cart' => $cart]);

        return response()->json(['success' => true]);
    }

}

