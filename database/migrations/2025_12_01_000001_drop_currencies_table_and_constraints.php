<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('currencies');
    }

    public function down(): void
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('code', 3)->unique();
            $table->string('symbol', 5)->nullable();
            $table->decimal('rate_to_base', 12, 6)->default(1);
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        if (Schema::hasTable('carts') && Schema::hasColumn('carts', 'currency_code')) {
            Schema::table('carts', function (Blueprint $table) {
                $table->foreign('currency_code')->references('code')->on('currencies')->restrictOnDelete();
            });
        }

        if (Schema::hasTable('addresses') && Schema::hasColumn('addresses', 'currency_code')) {
            Schema::table('addresses', function (Blueprint $table) {
                $table->foreign('currency_code')->references('code')->on('currencies')->restrictOnDelete();
            });
        }
    }
};
