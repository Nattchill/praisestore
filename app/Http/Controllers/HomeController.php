<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->get();
        $featured = Product::where('featured', true)->where('is_active', true)->with('category')->take(8)->get();
        $newArrivals = Product::where('is_active', true)->with('category')->latest()->take(8)->get();
        return view('home', compact('categories', 'featured', 'newArrivals'));
    }
}
