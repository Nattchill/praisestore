<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number', 'user_id', 'customer_name', 'customer_email',
        'customer_phone', 'shipping_address', 'city',
        'subtotal', 'shipping', 'total', 'status', 'payment_method',
        'momo_phone', 'payment_status'
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
