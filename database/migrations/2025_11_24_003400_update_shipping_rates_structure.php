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
            if (Schema::hasColumn('shipping_rates', 'countries')) {
                $table->dropColumn('countries');
            }

            $table->enum('rate_basis', ['country', 'weight', 'quantity'])
                ->default('country')
                ->after('country_code');

            $table->enum('charge_type', ['flat', 'percent'])
                ->default('flat')
                ->after('rate_basis');

            $table->decimal('tax_percent', 6, 3)->default(0)->after('charge_type');
            $table->unsignedInteger('qty_min')->nullable()->after('weight_max');
            $table->unsignedInteger('qty_max')->nullable()->after('qty_min');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipping_rates', function (Blueprint $table) {
            $table->dropColumn([
                'rate_basis',
                'charge_type',
                'tax_percent',
                'qty_min',
                'qty_max',
            ]);
        });
    }
};
