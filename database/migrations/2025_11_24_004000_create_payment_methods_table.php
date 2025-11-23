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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('provider')->default('stripe');
            $table->string('stripe_type')->nullable()->index();
            $table->enum('fee_type', ['flat', 'percent'])->default('flat');
            $table->decimal('fee_amount', 10, 2)->default(0);
            $table->boolean('is_active')->default(true)->index();
            $table->string('badge')->nullable();
            $table->string('description')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
