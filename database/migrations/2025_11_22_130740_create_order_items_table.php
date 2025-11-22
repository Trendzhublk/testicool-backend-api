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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')->constrained()->cascadeOnDelete();

            $table->enum('provider', ['stripe'])->default('stripe');
            $table->string('provider_ref')->nullable(); // payment_intent id

            $table->decimal('amount', 10, 2);
            $table->string('currency_code', 3);

            $table->enum('status', [
                'initiated',
                'succeeded',
                'failed',
                'refunded'
            ])->default('initiated');

            $table->json('payload')->nullable();

            $table->timestamps();

            $table->index(['order_id', 'provider', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
