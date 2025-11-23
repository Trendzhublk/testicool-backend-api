<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingAgent extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'region',
        'country_code',
        'priority',
        'is_active',
        'notes',
        'metadata',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'priority' => 'integer',
        'metadata' => 'array',
    ];

    public function rates()
    {
        return $this->hasMany(ShippingRate::class, 'shipping_agent_id');
    }
}
