<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_number',
        'customer_id',
        'user_id',
        'subtotal',
        'tax',
        'discount',
        'total',
        'status',
        'payment_method',
        'payment_status',
        'notes',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $order->order_number = 'ORD' . date('YmdHis') . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        });
    }
}