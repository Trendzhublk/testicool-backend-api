<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedback';

    protected $fillable = [
        'address_id',
        'ratings',
        'comment',
        'image_path',
        'status',
    ];

    protected $casts = [
        'ratings' => 'array',
    ];

    public function address()
    {
        return $this->belongsTo(Address::class);
    }
}
