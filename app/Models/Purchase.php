<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
// app/Models/Purchase.php
class Purchase extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'purchase_number',
        'supplier_id',
        'user_id',
        'subtotal',
        'tax',
        'total',
        'status',
        'notes',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($purchase) {
            $purchase->purchase_number = 'PUR' . date('YmdHis') . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        });
    }
}