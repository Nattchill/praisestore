<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    // ─── DASHBOARD ───────────────────────────────────────────────
    public function dashboard()
    {
        $stats = [
            'products'   => Product::count(),
            'orders'     => Order::count(),
            'revenue'    => Order::where('status', '!=', 'cancelled')->sum('total'),
            'pending'    => Order::where('status', 'pending')->count(),
            'customers'  => User::where('is_admin', false)->count(),
            'categories' => Category::count(),
        ];

        $recentOrders = Order::with('items')->latest()->take(8)->get();

        $monthlyRevenue = Order::where('status', '!=', 'cancelled')
            ->where('created_at', '>=', now()->subMonths(6))
            ->selectRaw('EXTRACT(MONTH FROM created_at) as month, EXTRACT(YEAR FROM created_at) as year, SUM(total) as total')
            ->groupByRaw('EXTRACT(YEAR FROM created_at), EXTRACT(MONTH FROM created_at)')
            ->orderByRaw('EXTRACT(YEAR FROM created_at), EXTRACT(MONTH FROM created_at)')
            ->get();

        $topProducts = OrderItem::selectRaw('product_name, SUM(quantity) as total_sold, SUM(subtotal) as revenue')
            ->groupBy('product_name')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'monthlyRevenue', 'topProducts'));
    }

    // ─── PRODUCTS ────────────────────────────────────────────────
    public function products()
    {
        $query = Product::with('category')->latest();

        if (request('search')) {
            $query->where('name', 'like', '%' . request('search') . '%');
        }
        if (request('category')) {
            $query->where('category_id', request('category'));
        }
        if (request('status') === 'active') {
            $query->where('is_active', true);
        } elseif (request('status') === 'inactive') {
            $query->where('is_active', false);
        } elseif (request('status') === 'featured') {
            $query->where('featured', true);
        } elseif (request('status') === 'out_of_stock') {
            $query->where('stock', 0);
        }

        $products = $query->paginate(15)->withQueryString();
        return view('admin.products', compact('products'));
    }

    public function createProduct()
    {
        $categories = Category::all();
        return view('admin.product-form', compact('categories'));
    }

    public function storeProduct(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'old_price'   => 'nullable|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'featured'    => 'boolean',
            'is_active'   => 'boolean',
            'image_file'  => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'image'       => 'nullable|string',
        ]);

        if ($request->hasFile('image_file')) {
            $data['image'] = '/storage/' . $request->file('image_file')->store('products', 'public');
        } elseif (empty($data['image'])) {
            $data['image'] = null;
        }
        unset($data['image_file']);

        $data['slug']      = Str::slug($data['name']) . '-' . Str::random(4);
        $data['featured']  = $request->boolean('featured');
        $data['is_active'] = $request->boolean('is_active', true);
        Product::create($data);
        return redirect()->route('admin.products')->with('success', 'Product created successfully.');
    }

    public function editProduct(Product $product)
    {
        $categories = Category::all();
        return view('admin.product-form', compact('product', 'categories'));
    }

    public function updateProduct(Request $request, Product $product)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'old_price'   => 'nullable|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'featured'    => 'boolean',
            'is_active'   => 'boolean',
            'image_file'  => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'image'       => 'nullable|string',
        ]);

        if ($request->hasFile('image_file')) {
            // Delete old uploaded image if it exists
            if ($product->image && str_starts_with($product->image, '/storage/')) {
                $old = str_replace('/storage/', '', $product->image);
                \Illuminate\Support\Facades\Storage::disk('public')->delete($old);
            }
            $data['image'] = '/storage/' . $request->file('image_file')->store('products', 'public');
        } elseif (empty($data['image'])) {
            // Keep existing image if no new file and no URL provided
            $data['image'] = $product->image;
        }
        unset($data['image_file']);

        $data['featured']  = $request->boolean('featured');
        $data['is_active'] = $request->boolean('is_active', true);
        $product->update($data);
        return redirect()->route('admin.products')->with('success', 'Product updated successfully.');
    }

    public function deleteProduct(Product $product)
    {
        $product->delete();
        return back()->with('success', 'Product deleted.');
    }

    // ─── CATEGORIES ──────────────────────────────────────────────
    public function categories()
    {
        $categories = Category::withCount('products')->latest()->paginate(15);
        return view('admin.categories', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $data = $request->validate([
            'name'  => 'required|string|max:255|unique:categories,name',
            'image' => 'nullable|url',
        ]);
        $data['slug'] = Str::slug($data['name']);
        Category::create($data);
        return back()->with('success', 'Category created successfully.');
    }

    public function updateCategory(Request $request, Category $category)
    {
        $data = $request->validate([
            'name'  => 'required|string|max:255|unique:categories,name,' . $category->id,
            'image' => 'nullable|url',
        ]);
        $data['slug'] = Str::slug($data['name']);
        $category->update($data);
        return back()->with('success', 'Category updated successfully.');
    }

    public function deleteCategory(Category $category)
    {
        if ($category->products()->count() > 0) {
            return back()->with('error', 'Cannot delete category with products. Reassign products first.');
        }
        $category->delete();
        return back()->with('success', 'Category deleted.');
    }

    // ─── CUSTOMERS ───────────────────────────────────────────────
    public function customers()
    {
        $customers = User::where('is_admin', false)
            ->withCount('orders')
            ->latest()
            ->paginate(15);
        return view('admin.customers', compact('customers'));
    }

    public function showCustomer(User $user)
    {
        $orders = Order::where('user_id', $user->id)->with('items')->latest()->get();
        $totalSpent = $orders->where('status', '!=', 'cancelled')->sum('total');
        return view('admin.customer-detail', compact('user', 'orders', 'totalSpent'));
    }

    public function toggleCustomerStatus(User $user)
    {
        if ($user->is_admin) {
            return back()->with('error', 'Cannot modify admin accounts.');
        }
        // We use email_verified_at as a soft block toggle
        $user->update(['email_verified_at' => $user->email_verified_at ? null : now()]);
        return back()->with('success', 'Customer status updated.');
    }

    // ─── PAYMENTS ────────────────────────────────────────────────
    public function payments()
    {
        $query = Order::with('user')->latest();
        if (request('method') && request('method') !== 'all') {
            $query->where('payment_method', request('method'));
        }
        if (request('status') && request('status') !== 'all') {
            $query->where('payment_status', request('status'));
        }
        $payments = $query->paginate(20)->withQueryString();
        $stats = [
            'total_paid'    => Order::where('payment_status', 'paid')->sum('total'),
            'pending_momo'  => Order::where('payment_method', 'mobile_money')->where('payment_status', 'pending')->count(),
            'total_momo'    => Order::where('payment_method', 'mobile_money')->where('payment_status', 'paid')->sum('total'),
            'total_cod'     => Order::where('payment_method', 'cash_on_delivery')->count(),
        ];
        return view('admin.payments', compact('payments', 'stats'));
    }

    public function updatePaymentStatus(Request $request, Order $order)
    {
        $request->validate(['payment_status' => 'required|in:pending,paid,failed']);
        $order->update(['payment_status' => $request->payment_status]);
        return back()->with('success', 'Payment status updated.');
    }

    // ─── ORDERS ──────────────────────────────────────────────────
    public function orders()
    {
        $query = Order::with('items')->latest();
        if (request('status') && request('status') !== 'all') {
            $query->where('status', request('status'));
        }
        $orders = $query->paginate(15)->withQueryString();
        return view('admin.orders', compact('orders'));
    }

    public function showOrder(Order $order)
    {
        $order->load('items.product');
        return view('admin.order-detail', compact('order'));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate(['status' => 'required|in:pending,processing,shipped,delivered,cancelled']);
        $order->update(['status' => $request->status]);
        return back()->with('success', 'Order status updated.');
    }

    // ─── REPORTS ─────────────────────────────────────────────────
    public function reports()
    {
        $period = request('period', '30');

        $salesData = Order::where('status', '!=', 'cancelled')
            ->where('created_at', '>=', now()->subDays($period))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as orders, SUM(total) as revenue')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $topCategories = OrderItem::join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->selectRaw('categories.name, SUM(order_items.quantity) as total_sold, SUM(order_items.subtotal) as revenue')
            ->groupBy('categories.name')
            ->orderByDesc('revenue')
            ->get();

        $topProducts = OrderItem::selectRaw('product_name, SUM(quantity) as total_sold, SUM(subtotal) as revenue')
            ->groupBy('product_name')
            ->orderByDesc('revenue')
            ->take(10)
            ->get();

        $ordersByStatus = Order::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $summary = [
            'total_revenue' => Order::where('status', '!=', 'cancelled')->where('created_at', '>=', now()->subDays($period))->sum('total'),
            'total_orders'  => Order::where('created_at', '>=', now()->subDays($period))->count(),
            'avg_order'     => Order::where('status', '!=', 'cancelled')->where('created_at', '>=', now()->subDays($period))->avg('total') ?? 0,
            'new_customers' => User::where('is_admin', false)->where('created_at', '>=', now()->subDays($period))->count(),
        ];

        return view('admin.reports', compact('salesData', 'topCategories', 'topProducts', 'ordersByStatus', 'summary', 'period'));
    }

    // ─── SETTINGS ────────────────────────────────────────────────
    public function settings()
    {
        return view('admin.settings');
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'store_name'    => 'required|string|max:100',
            'store_email'   => 'required|email',
            'store_phone'   => 'required|string|max:20',
            'store_address' => 'required|string',
            'currency'      => 'required|string|max:10',
            'shipping_fee'  => 'required|numeric|min:0',
        ]);

        // Store in a simple JSON config file
        $settings = $request->only(['store_name', 'store_email', 'store_phone', 'store_address', 'currency', 'shipping_fee', 'store_description']);
        file_put_contents(storage_path('app/settings.json'), json_encode($settings, JSON_PRETTY_PRINT));

        return back()->with('success', 'Settings saved successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, auth()->user()->password)) {
            return back()->with('error', 'Current password is incorrect.');
        }

        auth()->user()->update(['password' => Hash::make($request->password)]);
        return back()->with('success', 'Password updated successfully.');
    }
}
