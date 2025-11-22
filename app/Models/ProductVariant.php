<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'sku',
        'color_id',
        'size_id',
        'price_override',
        'stock_qty',
        'weight',
        'is_active',
    ];

    protected $casts = [
        'price_override' => 'decimal:2',
        'stock_qty' => 'integer',
        'weight' => 'decimal:3',
        'is_active' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'variant_id');
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'variant_id');
    }

    public function wishlistItems()
    {
        return $this->hasMany(WishlistItem::class, 'variant_id');
    }

    public function orderLines()
    {
        return $this->hasMany(Order::class, 'variant_id');
    }
}
