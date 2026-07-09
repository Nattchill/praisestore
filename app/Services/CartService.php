<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Session;

class CartService
{
    const SESSION_KEY = 'cart';

    public function get(): array
    {
        return Session::get(self::SESSION_KEY, []);
    }

    public function add(int $productId, int $quantity = 1): void
    {
        $cart = $this->get();
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $product = Product::findOrFail($productId);
            $cart[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'image' => $product->image,
                'quantity' => $quantity,
            ];
        }
        Session::put(self::SESSION_KEY, $cart);
    }

    public function update(int $productId, int $quantity): void
    {
        $cart = $this->get();
        if ($quantity <= 0) {
            $this->remove($productId);
            return;
        }
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = $quantity;
            Session::put(self::SESSION_KEY, $cart);
        }
    }

    public function remove(int $productId): void
    {
        $cart = $this->get();
        unset($cart[$productId]);
        Session::put(self::SESSION_KEY, $cart);
    }

    public function clear(): void
    {
        Session::forget(self::SESSION_KEY);
    }

    public function total(): float
    {
        return array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $this->get()));
    }

    public function count(): int
    {
        return array_sum(array_column($this->get(), 'quantity'));
    }
}
