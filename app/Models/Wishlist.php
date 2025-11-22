<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
    ];

    public function items()
    {
        return $this->hasMany(WishlistItem::class);
    }
}
