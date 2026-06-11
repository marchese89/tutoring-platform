<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Utility\Cart;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function show(Request $request): View
    {
        ['items' => $items, 'total' => $total] = $this->summary($request);

        return view('public.cart', compact('items', 'total'));
    }

    public function checkout(Request $request): View
    {
        ['total' => $total] = $this->summary($request);

        return view('public.checkout', compact('total'));
    }

    private function summary(Request $request): array
    {
        $cart = $request->session()->get('cart');

        if (! $cart instanceof Cart) {
            return [
                'items' => [],
                'total' => 0,
            ];
        }

        return [
            'items' => array_map(fn ($item) => [
                'id' => $item->id(),
                'type' => $item->type(),
                'name' => $item->name(),
                'price' => $item->price(),
                'remove_url' => route('cart.items.destroy', [
                    'id' => $item->id(),
                    'type' => $item->type(),
                ]),
            ], $cart->items()),
            'total' => $cart->total(),
        ];
    }
}
