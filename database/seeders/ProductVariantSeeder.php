<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Color;
use App\Models\Size;

class ProductVariantSeeder extends Seeder
{
    public function run(): void
    {
        $product = Product::first();
        $colors = Color::all();
        $sizes  = Size::all();

        foreach ($colors as $color) {
            foreach ($sizes as $size) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'color_id'   => $color->id,
                    'size_id'    => $size->id,

                    'sku'        => 'TC-' . strtoupper($color->name) . '-' . $size->name,

                    // your real price field:
                    'price_override' => 34.99,

                    // your real stock field:
                    'stock_qty'      => rand(10, 50),

                    'is_active'      => true,
                ]);
            }
        }
    }
}
