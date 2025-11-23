<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('shipping_rates', function (Blueprint $table) {
            $table->dropUnique('shipping_rates_code_unique');
            $table->unique(['code', 'region', 'country_code'], 'shipping_rates_code_region_country_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipping_rates', function (Blueprint $table) {
            $table->dropUnique('shipping_rates_code_region_country_unique');
            $table->unique('code');
        });
    }
};
