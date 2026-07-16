<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
  body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
  .wrap { max-width: 600px; margin: 30px auto; background: #fff; border-radius: 8px; overflow: hidden; }
  .header { background: linear-gradient(135deg, #bc1888, #e1306c); padding: 30px; text-align: center; color: #fff; }
  .header h1 { margin: 0; font-size: 24px; }
  .header p { margin: 6px 0 0; opacity: .85; }
  .body { padding: 30px; }
  .badge { display: inline-block; background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; border-radius: 20px; padding: 4px 14px; font-size: 13px; margin-bottom: 20px; }
  table { width: 100%; border-collapse: collapse; margin: 20px 0; }
  th { background: #f9fafb; text-align: left; padding: 10px 12px; font-size: 13px; color: #6b7280; border-bottom: 1px solid #e5e7eb; }
  td { padding: 10px 12px; border-bottom: 1px solid #f3f4f6; font-size: 14px; }
  .totals td { font-weight: 600; }
  .totals .grand td { color: #bc1888; font-size: 16px; }
  .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin: 20px 0; }
  .info-box { background: #f9fafb; border-radius: 6px; padding: 14px; }
  .info-box h4 { margin: 0 0 8px; font-size: 12px; text-transform: uppercase; color: #9ca3af; }
  .info-box p { margin: 0; font-size: 14px; color: #111827; line-height: 1.6; }
  .footer { background: #f9fafb; padding: 20px 30px; text-align: center; font-size: 12px; color: #9ca3af; }
  .btn { display: inline-block; background: linear-gradient(135deg, #bc1888, #e1306c); color: #fff; text-decoration: none; padding: 12px 28px; border-radius: 6px; font-weight: 600; margin: 16px 0; }
</style>
</head>
<body>
<div class="wrap">
  <div class="header">
    <h1>🛍️ PraiseStore</h1>
    <p>Order Confirmed!</p>
  </div>
  <div class="body">
    <span class="badge">✅ Order Placed Successfully</span>
    <p>Hi <strong>{{ $order->customer_name }}</strong>, thank you for your order! We've received it and will start processing it shortly.</p>

    <p><strong>Order Number:</strong> {{ $order->order_number }}<br>
    <strong>Date:</strong> {{ $order->created_at->format('d M Y, H:i') }}</p>

    <table>
      <thead>
        <tr>
          <th>Item</th>
          <th>Qty</th>
          <th>Price</th>
          <th>Subtotal</th>
        </tr>
      </thead>
      <tbody>
        @foreach($order->items as $item)
        <tr>
          <td>{{ $item->product_name }}</td>
          <td>{{ $item->quantity }}</td>
          <td>RWF {{ number_format($item->price) }}</td>
          <td>RWF {{ number_format($item->subtotal) }}</td>
        </tr>
        @endforeach
      </tbody>
      <tfoot class="totals">
        <tr><td colspan="3">Subtotal</td><td>RWF {{ number_format($order->subtotal) }}</td></tr>
        <tr><td colspan="3">Shipping</td><td>RWF {{ number_format($order->shipping) }}</td></tr>
        <tr class="grand"><td colspan="3">Total</td><td>RWF {{ number_format($order->total) }}</td></tr>
      </tfoot>
    </table>

    <div class="info-grid">
      <div class="info-box">
        <h4>Shipping To</h4>
        <p>{{ $order->shipping_address }}<br>{{ $order->city }}</p>
      </div>
      <div class="info-box">
        <h4>Payment</h4>
        <p>{{ $order->payment_method === 'mobile_money' ? '📱 Mobile Money' : '💵 Cash on Delivery' }}<br>
        Status: {{ ucfirst($order->payment_status) }}</p>
      </div>
    </div>

    <div style="text-align:center">
      <a href="{{ config('app.url') }}/order/confirmation/{{ $order->order_number }}" class="btn">View Order Details</a>
    </div>

    <p style="font-size:13px;color:#6b7280">Questions? Reply to this email or contact us at <a href="mailto:davidfnatt2002@gmail.com">davidfnatt2002@gmail.com</a> or WhatsApp +250 795 9151.</p>
  </div>
  <div class="footer">
    © {{ date('Y') }} PraiseStore · Kigali, Rwanda<br>
    You received this email because you placed an order on PraiseStore.
  </div>
</div>
</body>
</html>
