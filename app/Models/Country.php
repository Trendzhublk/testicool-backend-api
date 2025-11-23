<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $primaryKey = 'code';
    protected $keyType = 'string';

    protected $fillable = [
        'code',
        'name',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'country_code', 'code');
    }

    public function addresses()
    {
        return $this->hasMany(Address::class, 'country_code', 'code');
    }
}
