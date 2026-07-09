<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class ShopController extends Controller
{
    public function index()
    {
        $query = Product::where('is_active', true)->with('category');

        if (request('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', request('category')));
        }

        if (request('search')) {
            $query->where('name', 'like', '%' . request('search') . '%');
        }

        if (request('sort') === 'price_asc') {
            $query->orderBy('price');
        } elseif (request('sort') === 'price_desc') {
            $query->orderByDesc('price');
        } else {
            $query->latest();
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::all();

        return view('shop', compact('products', 'categories'));
    }

    public function show(string $slug)
    {
        $product = Product::where('slug', $slug)->where('is_active', true)->with('category')->firstOrFail();
        $related = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(4)->get();
        return view('product', compact('product', 'related'));
    }
}
