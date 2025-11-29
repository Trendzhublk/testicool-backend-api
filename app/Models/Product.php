<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'base_price',
        'in_stock',
        'is_active',
        'is_featured',
        'cover_image',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'in_stock' => 'boolean',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function wishlistItems()
    {
        return $this->hasMany(WishlistItem::class);
    }

    public function orderLines()
    {
        return $this->hasMany(Order::class);
    }

    public function getCoverImageUrlAttribute()
    {
        $cover = $this->images->firstWhere('sort_order', 1);
        $path = $cover?->path ?? $this->images->sortBy('sort_order')->first()?->path;

        if (!$path) {
            return null;
        }

        $base = rtrim(config('filesystems.disks.s3.url', config('app.asset_url', '')), '/');
        if ($base && !str_starts_with($path, 'http')) {
            return $base . '/' . ltrim($path, '/');
        }

        return $path;
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
