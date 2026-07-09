<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Welcome splash (after login)
Route::get('/welcome', function () {
    if (!auth()->check()) return redirect()->route('login');
    $user = auth()->user();
    $redirectUrl = $user->is_admin ? route('admin.dashboard') : route('customer.dashboard');
    return view('welcome-splash', [
        'redirect_url'   => $redirectUrl,
        'redirect_delay' => 3,
    ]);
})->name('welcome.splash')->middleware(['auth:sanctum', config('jetstream.auth_session')]);

// Chat routes (customer)
Route::middleware(['auth:sanctum', config('jetstream.auth_session')])->group(function () {
    Route::post('/chat/send', [ChatController::class, 'send'])->name('chat.send');
    Route::get('/chat/fetch', [ChatController::class, 'fetch'])->name('chat.fetch');
    Route::get('/chat/unread', [ChatController::class, 'unread'])->name('chat.unread');
});

// Chat routes (admin)
Route::prefix('admin/chat')->middleware(['auth:sanctum', config('jetstream.auth_session'), 'admin'])->group(function () {
    Route::get('/conversations', [ChatController::class, 'adminConversations'])->name('admin.chat.conversations');
    Route::get('/fetch/{userId}', [ChatController::class, 'adminFetch'])->name('admin.chat.fetch');
    Route::get('/unread', [ChatController::class, 'adminUnread'])->name('admin.chat.unread');
});

// Google OAuth
Route::get('/auth/google', [SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/shop/{slug}', [ShopController::class, 'show'])->name('product.show');

// Cart
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/{productId}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{productId}', [CartController::class, 'remove'])->name('cart.remove');

// Checkout
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/order/confirmation/{orderNumber}', [CheckoutController::class, 'confirmation'])->name('order.confirmation');

// Auth routes (Jetstream)
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('welcome.splash');
    })->name('dashboard');
});

// Customer dashboard routes
Route::prefix('account')->name('customer.')->middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::get('/', [CustomerController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [CustomerController::class, 'profile'])->name('profile');
    Route::post('/profile/avatar', [CustomerController::class, 'updateAvatar'])->name('profile.avatar');
    Route::post('/profile', [CustomerController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/password', [CustomerController::class, 'updatePassword'])->name('profile.password');
    Route::get('/orders', [CustomerController::class, 'orders'])->name('orders');
    Route::get('/orders/track/{orderNumber}', [CustomerController::class, 'trackOrder'])->name('track');
    Route::post('/orders/search', [CustomerController::class, 'searchOrder'])->name('orders.search');
});

// Admin routes
Route::prefix('admin')->name('admin.')->middleware(['auth:sanctum', config('jetstream.auth_session'), 'admin'])->group(function () {

    // Dashboard
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // Products
    Route::get('/products', [AdminController::class, 'products'])->name('products');
    Route::get('/products/create', [AdminController::class, 'createProduct'])->name('products.create');
    Route::post('/products', [AdminController::class, 'storeProduct'])->name('products.store');
    Route::get('/products/{product}/edit', [AdminController::class, 'editProduct'])->name('products.edit');
    Route::put('/products/{product}', [AdminController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/{product}', [AdminController::class, 'deleteProduct'])->name('products.delete');

    // Categories
    Route::get('/categories', [AdminController::class, 'categories'])->name('categories');
    Route::post('/categories', [AdminController::class, 'storeCategory'])->name('categories.store');
    Route::put('/categories/{category}', [AdminController::class, 'updateCategory'])->name('categories.update');
    Route::delete('/categories/{category}', [AdminController::class, 'deleteCategory'])->name('categories.delete');

    // Customers
    Route::get('/customers', [AdminController::class, 'customers'])->name('customers');
    Route::get('/customers/{user}', [AdminController::class, 'showCustomer'])->name('customers.show');
    Route::patch('/customers/{user}/toggle', [AdminController::class, 'toggleCustomerStatus'])->name('customers.toggle');

    // Orders
    Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
    Route::get('/orders/{order}', [AdminController::class, 'showOrder'])->name('orders.show');
    Route::patch('/orders/{order}/status', [AdminController::class, 'updateOrderStatus'])->name('orders.status');

    // Payments
    Route::get('/payments', [AdminController::class, 'payments'])->name('payments');
    Route::patch('/payments/{order}/status', [AdminController::class, 'updatePaymentStatus'])->name('payments.status');

    // Reports
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');

    // Settings
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    Route::post('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');
    Route::post('/settings/password', [AdminController::class, 'updatePassword'])->name('settings.password');
});
