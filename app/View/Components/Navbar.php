<?php

namespace App\View\Components;

use App\Http\Utility\Cart;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\View\Component;

class Navbar extends Component
{
    public readonly int $cartCount;

    public function __construct(Request $request)
    {
        $cart = $request->session()->get('cart');

        $this->cartCount = $cart instanceof Cart ? $cart->count() : 0;
    }

    public function render(): View
    {
        return view('components.navbar');
    }
}
