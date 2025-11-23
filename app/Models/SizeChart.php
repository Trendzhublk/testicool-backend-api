<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SizeChart extends Model
{
    use HasFactory;

    protected $fillable = [
        'size_id',
        'unit',
        'min_value',
        'max_value',
    ];

    protected $casts = [
        'min_value' => 'integer',
        'max_value' => 'integer',
    ];

    public function size()
    {
        return $this->belongsTo(Size::class);
    }
}
