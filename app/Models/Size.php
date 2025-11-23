<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function sizeCharts()
    {
        return $this->hasMany(SizeChart::class);
    }

    // Alias used in eager loads (variants.size.charts)
    public function charts()
    {
        return $this->sizeCharts();
    }
}
