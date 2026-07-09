<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    // ── Customer: send message + trigger auto-reply ──────────────
    public function send(Request $request)
    {
        $request->validate(['message' => 'required|string|max:1000']);

        ChatMessage::create([
            'user_id'  => auth()->id(),
            'message'  => $request->message,
            'is_admin' => false,
            'is_read'  => false,
        ]);

        // Auto-reply after short delay simulation
        $reply = $this->autoReply($request->message);

        ChatMessage::create([
            'user_id'  => auth()->id(),
            'message'  => $reply,
            'is_admin' => true,
            'is_read'  => false,
        ]);

        return response()->json(['ok' => true]);
    }

    // ── Auto-reply engine ─────────────────────────────────────────
    private function autoReply(string $message): string
    {
        $msg = strtolower(trim($message));

        // Greetings
        if ($this->matches($msg, ['hello', 'hi', 'hey', 'good morning', 'good afternoon', 'good evening', 'howdy', 'sup', 'helo', 'hii'])) {
            $name = auth()->user()->name ?? 'there';
            return "👋 Hello {$name}! Welcome to PraiseStore support. How can I help you today? You can ask me about orders, products, delivery, payments, or returns.";
        }

        // How are you
        if ($this->matches($msg, ['how are you', 'how r you', 'how are u', 'are you okay'])) {
            return "😊 I'm doing great, thank you for asking! I'm here to help you with anything related to PraiseStore. What can I do for you?";
        }

        // Order tracking
        if ($this->matches($msg, ['track', 'where is my order', 'order status', 'my order', 'order number', 'delivery status', 'shipped', 'when will i receive'])) {
            $orders = Order::where('user_id', auth()->id())->latest()->take(3)->get();
            if ($orders->isEmpty()) {
                return "📦 I couldn't find any orders linked to your account. If you placed an order as a guest, please visit the order tracking page and enter your order number. You can also reach us at davidfnatt2002@gmail.com.";
            }
            $list = $orders->map(fn($o) => "• #{$o->order_number} — Status: **{$o->status}** (RWF " . number_format($o->total) . ")")->join("\n");
            return "📦 Here are your recent orders:\n\n{$list}\n\nFor detailed tracking, visit My Account → My Orders.";
        }

        // Cancel order
        if ($this->matches($msg, ['cancel', 'cancel order', 'i want to cancel', 'stop my order'])) {
            return "❌ To cancel an order, please go to My Account → My Orders and select the order you wish to cancel. Orders can only be cancelled if they are still in **Pending** status. If your order is already processing or shipped, please contact us at davidfnatt2002@gmail.com or call +250 795 9151.";
        }

        // Payment
        if ($this->matches($msg, ['payment', 'pay', 'momo', 'mobile money', 'cash', 'cod', 'how to pay', 'payment method', 'accepted payment'])) {
            return "💳 PraiseStore accepts the following payment methods:\n\n• 📱 **Mobile Money (MoMo)** — MTN & Airtel\n• 💵 **Cash on Delivery (COD)** — Pay when your order arrives\n\nAll payments are secure. For MoMo, you'll receive instructions after placing your order.";
        }

        // Delivery / shipping
        if ($this->matches($msg, ['delivery', 'shipping', 'deliver', 'how long', 'shipping fee', 'delivery time', 'when will', 'how many days'])) {
            return "🚚 Delivery details:\n\n• **Kigali:** 1–2 business days\n• **Other provinces:** 2–4 business days\n• **Shipping fee:** Starting from RWF 1,000 (calculated at checkout)\n\nYou'll receive an SMS/email update once your order is shipped!";
        }

        // Return / refund
        if ($this->matches($msg, ['return', 'refund', 'exchange', 'wrong item', 'damaged', 'broken', 'not what i ordered', 'send back'])) {
            return "🔄 Our return & refund policy:\n\n• Returns accepted within **7 days** of delivery\n• Item must be unused and in original packaging\n• For damaged or wrong items, we cover return shipping\n\nTo start a return, email us at davidfnatt2002@gmail.com with your order number and photos of the item.";
        }

        // Products / stock
        if ($this->matches($msg, ['product', 'stock', 'available', 'in stock', 'out of stock', 'size', 'color', 'do you have', 'sell'])) {
            $count = Product::where('is_active', true)->count();
            $cats  = Category::pluck('name')->join(', ');
            return "🛍️ We currently have **{$count} active products** across categories: {$cats}.\n\nBrowse our full collection at the Shop page. Use the search bar or category filters to find exactly what you need!";
        }

        // Price / discount
        if ($this->matches($msg, ['price', 'cost', 'how much', 'discount', 'sale', 'offer', 'promo', 'coupon', 'deal', 'cheap'])) {
            return "🏷️ Our prices are listed in **Rwandan Francs (RWF)** on each product page. We regularly run promotions and sales — check the homepage for featured deals and new arrivals!\n\nFor bulk orders or special pricing, contact us at davidfnatt2002@gmail.com.";
        }

        // Account / login / register
        if ($this->matches($msg, ['account', 'login', 'register', 'sign up', 'sign in', 'forgot password', 'reset password', 'password', 'create account'])) {
            return "🔐 Account help:\n\n• **Register:** Click 'Register' in the top bar\n• **Login:** Click 'Login' and enter your email & password\n• **Forgot password:** On the login page, click 'Forgot password?' to reset via email\n• **Google sign-in:** Use the 'Continue with Google' button for quick access\n\nNeed more help? Email us at davidfnatt2002@gmail.com.";
        }

        // Contact / support
        if ($this->matches($msg, ['contact', 'support', 'help', 'talk to human', 'speak to agent', 'customer service', 'phone', 'email', 'reach you', 'whatsapp'])) {
            return "📞 You can reach PraiseStore support through:\n\n• 📧 Email: davidfnatt2002@gmail.com\n• 📱 Phone/WhatsApp: +250 795 9151\n• 📍 Location: Kigali, Rwanda\n• 🕐 Hours: Mon–Sat, 8am–6pm\n\nWe typically respond within 2 hours during business hours.";
        }

        // Thank you
        if ($this->matches($msg, ['thank', 'thanks', 'thank you', 'thx', 'merci', 'appreciate'])) {
            return "😊 You're very welcome! Is there anything else I can help you with? Happy shopping at PraiseStore! 🛍️";
        }

        // Goodbye
        if ($this->matches($msg, ['bye', 'goodbye', 'see you', 'later', 'ciao', 'ok bye', 'good bye'])) {
            return "👋 Goodbye! Thank you for visiting PraiseStore. Have a wonderful day and happy shopping! 🛍️";
        }

        // Categories
        if ($this->matches($msg, ['categor', 'women', 'men', 'kids', 'accessories', 'clothing', 'fashion', 'what do you sell'])) {
            $cats = Category::pluck('name')->join(', ');
            return "👗 PraiseStore offers fashion for everyone! Our categories include: **{$cats}**.\n\nVisit the Shop page to browse all collections.";
        }

        // Location
        if ($this->matches($msg, ['location', 'where are you', 'address', 'kigali', 'physical store', 'shop location'])) {
            return "📍 PraiseStore is based in **Kigali, Rwanda**. We are primarily an online store delivering across Rwanda.\n\nFor inquiries about physical pickup, contact us at +250 795 9151.";
        }

        // Default fallback
        return "🤖 Thanks for your message! I'm the PraiseStore assistant and I can help with:\n\n• 📦 Order tracking & status\n• 🚚 Delivery & shipping info\n• 💳 Payment methods\n• 🔄 Returns & refunds\n• 🛍️ Products & availability\n• 🔐 Account & login help\n• 📞 Contact & support\n\nJust ask me anything about these topics, or contact us directly at davidfnatt2002@gmail.com or +250 795 9151.";
    }

    // ── Helper: check if message contains any keyword ─────────────
    private function matches(string $msg, array $keywords): bool
    {
        foreach ($keywords as $kw) {
            if (str_contains($msg, $kw)) return true;
        }
        return false;
    }

    // ── Customer: fetch conversation ──────────────────────────────
    public function fetch()
    {
        $messages = ChatMessage::where('user_id', auth()->id())
            ->orderBy('created_at')
            ->get(['id', 'message', 'is_admin', 'created_at']);

        ChatMessage::where('user_id', auth()->id())
            ->where('is_admin', true)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json($messages);
    }

    // ── Customer: unread count ────────────────────────────────────
    public function unread()
    {
        $count = ChatMessage::where('user_id', auth()->id())
            ->where('is_admin', true)
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }

    // ── Admin: list conversations (read-only view) ────────────────
    public function adminConversations()
    {
        $users = ChatMessage::selectRaw('user_id, MAX(created_at) as last_at')
            ->groupBy('user_id')
            ->orderByDesc('last_at')
            ->with('user')
            ->get()
            ->map(function ($row) {
                $last = ChatMessage::where('user_id', $row->user_id)
                    ->orderByDesc('created_at')
                    ->value('message');
                return [
                    'user_id' => $row->user_id,
                    'name'    => $row->user->name ?? 'Guest',
                    'email'   => $row->user->email ?? '',
                    'last_at' => $row->last_at,
                    'preview' => $last ? substr($last, 0, 40) : '',
                ];
            });

        return response()->json($users);
    }

    // ── Admin: fetch one user's conversation ──────────────────────
    public function adminFetch($userId)
    {
        $messages = ChatMessage::where('user_id', $userId)
            ->orderBy('created_at')
            ->get(['id', 'message', 'is_admin', 'created_at']);

        return response()->json($messages);
    }

    // ── Admin: unread count (user messages not yet seen) ──────────
    public function adminUnread()
    {
        $count = ChatMessage::where('is_admin', false)->where('is_read', false)->count();
        return response()->json(['count' => $count]);
    }
}
