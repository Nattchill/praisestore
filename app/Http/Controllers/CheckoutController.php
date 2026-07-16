<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmation;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function __construct(private CartService $cart) {}

    public function index()
    {
        $items = $this->cart->get();
        if (empty($items)) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }
        $total = $this->cart->total();
        $shipping = 2000;
        return view('checkout', compact('items', 'total', 'shipping'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name'    => 'required|string|max:255',
            'customer_email'   => 'required|email',
            'customer_phone'   => 'required|string|max:20',
            'shipping_address' => 'required|string',
            'city'             => 'required|string|max:100',
            'payment_method'   => 'required|in:cash_on_delivery,mobile_money',
            'momo_phone'       => 'required_if:payment_method,mobile_money|nullable|string|max:20',
        ]);

        $items = $this->cart->get();
        if (empty($items)) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }

        $subtotal = $this->cart->total();
        $shipping = 2000;
        $isMomo   = $request->payment_method === 'mobile_money';

        $order = Order::create([
            'order_number'     => 'PS-' . strtoupper(Str::random(8)),
            'user_id'          => auth()->id(),
            'customer_name'    => $request->customer_name,
            'customer_email'   => $request->customer_email,
            'customer_phone'   => $request->customer_phone,
            'shipping_address' => $request->shipping_address,
            'city'             => $request->city,
            'subtotal'         => $subtotal,
            'shipping'         => $shipping,
            'total'            => $subtotal + $shipping,
            'payment_method'   => $request->payment_method,
            'momo_phone'       => $isMomo ? $request->momo_phone : null,
            'payment_status'   => $isMomo ? 'pending' : 'paid',
        ]);

        foreach ($items as $item) {
            OrderItem::create([
                'order_id'     => $order->id,
                'product_id'   => $item['id'],
                'product_name' => $item['name'],
                'price'        => $item['price'],
                'quantity'     => $item['quantity'],
                'subtotal'     => $item['price'] * $item['quantity'],
            ]);
            Product::where('id', $item['id'])->decrement('stock', $item['quantity']);
        }

        $this->cart->clear();

        try {
            Mail::to($order->customer_email)->send(new OrderConfirmation($order->load('items')));
        } catch (\Exception) {
            // Mail failure must not block the order
        }

        return redirect()->route('order.confirmation', $order->order_number);
    }

    public function confirmation(string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->with('items')->firstOrFail();
        return view('order-confirmation', compact('order'));
    }
}
