<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::create([
            'title' => 'Ultra Soft Modal Boxer Brief',
            'slug' => Str::slug('Ultra Soft Modal Boxer Brief'),
            'description' => 'Engineered with heat-conductive fabrics and a 3D-pouch design for superior support, breathability, and temperature regulation.',
            'base_price' => 34.99,
            'in_stock' => true,
            'is_active' => true,
            'is_featured' => true,
            'cover_image' => '/images/products/brief-black-1.png',
            'meta_title' => 'Ultra Soft Modal Boxer Brief',
            'meta_description' => 'Everyday comfort with TestiCool heat-regulating pouch.',
        ]);
    }
}
