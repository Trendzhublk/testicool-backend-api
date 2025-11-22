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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->restrictOnDelete();
            $table->foreignId('variant_id')->nullable()
                ->constrained('product_variants')->restrictOnDelete();

            $table->string('sku_snapshot')->nullable();
            $table->string('title_snapshot');
            $table->decimal('price_snapshot', 10, 2);
            $table->unsignedInteger('qty')->default(1);
            $table->decimal('line_total', 10, 2);

            $table->json('meta')->nullable();

            $table->timestamps();

            $table->index(['order_id', 'product_id', 'variant_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
