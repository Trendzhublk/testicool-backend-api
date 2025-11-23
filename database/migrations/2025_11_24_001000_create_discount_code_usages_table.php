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
        Schema::create('discount_code_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('discount_code_id')->constrained()->cascadeOnDelete();
            $table->string('email')->nullable()->index();
            $table->enum('status', ['reserved', 'redeemed', 'released'])->default('reserved');
            $table->timestamp('reserved_until')->nullable()->index();
            $table->timestamp('redeemed_at')->nullable();
            $table->string('stripe_checkout_session_id')->nullable()->index();
            $table->string('stripe_customer_id')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discount_code_usages');
    }
};
