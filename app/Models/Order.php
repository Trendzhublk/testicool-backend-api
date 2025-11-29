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
        'size_snapshot',
        'color_snapshot',
        'color_hex_snapshot',
        'title_snapshot',
        'price_snapshot',
        'qty',
        'line_total',
        'meta',
        'tracking_number',
        'status',
        'status_note',
        'shipping_agent_id',
        'sales_agent_id',
        'customer_email',
        'customer_name',
        'status_updated_at',
    ];

    protected $casts = [
        'price_snapshot' => 'decimal:2',
        'qty' => 'integer',
        'line_total' => 'decimal:2',
        'meta' => 'array',
        'status_updated_at' => 'datetime',
    ];

    public function parent()
    {
        return $this->belongsTo(self::class, 'order_id');
    }

    public function address()
    {
        return $this->belongsTo(Address::class, 'order_id');
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

    public function shippingAgent()
    {
        return $this->belongsTo(ShippingAgent::class, 'shipping_agent_id');
    }

    public function salesAgent()
    {
        return $this->belongsTo(User::class, 'sales_agent_id');
    }
}
