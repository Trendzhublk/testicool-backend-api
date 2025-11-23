<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Clean relevant tables
        \DB::table('model_has_permissions')->truncate();
        \DB::table('model_has_roles')->truncate();
        \DB::table('role_has_permissions')->truncate();
        \DB::table('permissions')->truncate();
        \DB::table('roles')->truncate();

        \App\Models\OrderItem::truncate();
        \App\Models\Order::truncate();

        \App\Models\CartItem::truncate();
        \App\Models\Cart::truncate();

        \App\Models\WishlistItem::truncate();
        \App\Models\Wishlist::truncate();

        \App\Models\Country::truncate();
        \App\Models\ProductVariant::truncate();
        \App\Models\ProductImage::truncate();
        \App\Models\Product::truncate();

        \App\Models\Color::truncate();
        \App\Models\Size::truncate();
        \App\Models\SizeChart::truncate();
        \App\Models\Currency::truncate();
        \App\Models\ShippingRate::truncate();
        \App\Models\ShippingAgent::truncate();
        \App\Models\SalesAgentAssignment::truncate();
        \App\Models\DiscountCodeUsage::truncate();
        \App\Models\DiscountCode::truncate();

        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Call Seeders
        $this->call([
            CountrySeeder::class,
            RoleSeeder::class,
            CurrencySeeder::class,
            ColorSeeder::class,
            SizeSeeder::class,
            SizeChartSeeder::class,
            ProductSeeder::class,
            ProductImageSeeder::class,
            ProductVariantSeeder::class,
            ShippingRateSeeder::class,
            ShippingAgentSeeder::class,
            SalesAgentAssignmentSeeder::class,
            DiscountCodeSeeder::class,
        ]);
    }
}
