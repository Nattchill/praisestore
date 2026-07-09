<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    // ── DASHBOARD ────────────────────────────────────────────────
    public function dashboard()
    {
        $user   = auth()->user();
        $orders = Order::where('user_id', $user->id)->latest()->take(5)->get();
        $stats  = [
            'total_orders'    => Order::where('user_id', $user->id)->count(),
            'pending'         => Order::where('user_id', $user->id)->where('status', 'pending')->count(),
            'delivered'       => Order::where('user_id', $user->id)->where('status', 'delivered')->count(),
            'total_spent'     => Order::where('user_id', $user->id)->where('status', '!=', 'cancelled')->sum('total'),
        ];
        return view('customer.dashboard', compact('user', 'orders', 'stats'));
    }

    // ── PROFILE ──────────────────────────────────────────────────
    public function profile()
    {
        return view('customer.profile', ['user' => auth()->user()]);
    }

    public function updateAvatar(Request $request)
    {
        $request->validate(['avatar' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048']);
        $user = auth()->user();
        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }
        $path = $request->file('avatar')->store('avatars', 'public');
        $user->forceFill(['profile_photo_path' => $path])->save();
        return back()->with('success', 'Profile picture updated successfully.');
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
        ]);
        $user->update($request->only('name', 'email', 'phone'));
        return back()->with('success', 'Profile updated successfully.');
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
        return back()->with('success', 'Password changed successfully.');
    }

    // ── ORDERS ───────────────────────────────────────────────────
    public function orders()
    {
        $orders = Order::where('user_id', auth()->id())
            ->with('items')
            ->latest()
            ->paginate(10);
        return view('customer.orders', compact('orders'));
    }

    public function trackOrder(string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where(function ($q) {
                $q->where('user_id', auth()->id())
                  ->orWhere('customer_email', auth()->user()->email);
            })
            ->with('items.product')
            ->firstOrFail();
        return view('customer.track-order', compact('order'));
    }

    public function searchOrder(Request $request)
    {
        $request->validate(['order_number' => 'required|string']);
        $order = Order::where('order_number', $request->order_number)
            ->where(function ($q) {
                $q->where('user_id', auth()->id())
                  ->orWhere('customer_email', auth()->user()->email);
            })
            ->first();
        if (!$order) {
            return back()->with('error', 'Order not found. Please check the order number.');
        }
        return redirect()->route('customer.track', $order->order_number);
    }
}
