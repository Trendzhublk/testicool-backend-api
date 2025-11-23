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
        Schema::create('countries', function (Blueprint $table) {
            $table->string('code', 2)->primary();
            $table->string('name');
            $table->timestamps();
        });

        Schema::table('addresses', function (Blueprint $table) {
            $table->string('country_code', 2)->default('US')->after('payment_status');
            $table->foreign('country_code')
                ->references('code')->on('countries')
                ->restrictOnDelete();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('country_code', 2)->nullable()->after('password');
            $table->foreign('country_code')
                ->references('code')->on('countries')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['country_code']);
            $table->dropColumn('country_code');
        });

        Schema::table('addresses', function (Blueprint $table) {
            $table->dropForeign(['country_code']);
            $table->dropColumn('country_code');
        });

        Schema::dropIfExists('countries');
    }
};
