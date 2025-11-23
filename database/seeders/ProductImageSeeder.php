<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductImage;

class ProductImageSeeder extends Seeder
{
    public function run(): void
    {
        $product = Product::first();

        // These should be real CDN/storage URLs later.
        // For now, keep as placeholders to verify UI binding.
        $images = [
            ['path' => '/images/products/brief-black-1.png', 'sort_order' => 1, 'alt_text' => 'Front view'],
            ['path' => '/images/products/brief-black-2.png', 'sort_order' => 2, 'alt_text' => 'Side view'],
            ['path' => '/images/products/brief-black-3.png', 'sort_order' => 3, 'alt_text' => 'Detail view'],
        ];

        foreach ($images as $img) {
            ProductImage::create([
                'product_id' => $product->id,
                'path' => $img['path'],
                'sort_order' => $img['sort_order'],
                'alt_text' => $img['alt_text'] ?? null,
            ]);
        }
    }
}
