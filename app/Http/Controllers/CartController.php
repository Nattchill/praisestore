<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(private CartService $cart) {}

    public function index()
    {
        $items = $this->cart->get();
        $total = $this->cart->total();
        return view('cart', compact('items', 'total'));
    }

    public function add(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please log in to add products to your cart.');
        }
        $request->validate(['product_id' => 'required|exists:products,id', 'quantity' => 'integer|min:1']);
        $this->cart->add($request->product_id, $request->quantity ?? 1);
        return back()->with('success', 'Product added to cart!');
    }

    public function update(Request $request, int $productId)
    {
        $request->validate(['quantity' => 'required|integer|min:0']);
        $this->cart->update($productId, $request->quantity);
        return back()->with('success', 'Cart updated.');
    }

    public function remove(int $productId)
    {
        $this->cart->remove($productId);
        return back()->with('success', 'Item removed from cart.');
    }
}
