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
        Schema::create('size_charts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('size_id')->constrained()->cascadeOnDelete();
            $table->enum('unit', ['CM', 'INCH']);
            $table->unsignedSmallInteger('min_value');
            $table->unsignedSmallInteger('max_value');
            $table->timestamps();

            $table->unique(['size_id', 'unit']); // one CM + one INCH per size
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('size_charts');
    }
};
