<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('size_snapshot')->nullable()->after('sku_snapshot');
            $table->string('color_snapshot')->nullable()->after('size_snapshot');
            $table->string('color_hex_snapshot', 7)->nullable()->after('color_snapshot');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['size_snapshot', 'color_snapshot', 'color_hex_snapshot']);
        });
    }
};
