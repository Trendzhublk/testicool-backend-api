<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'variant_id',
        'sku_snapshot',
        'title_snapshot',
        'price_snapshot',
        'qty',
        'line_total',
        'meta',
    ];

    protected $casts = [
        'price_snapshot' => 'decimal:2',
        'qty' => 'integer',
        'line_total' => 'decimal:2',
        'meta' => 'array',
    ];

    public function parent()
    {
        return $this->belongsTo(self::class, 'order_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
