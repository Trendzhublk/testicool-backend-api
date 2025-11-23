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
            $table->foreignId('shipping_agent_id')->nullable()->after('carrier')->constrained('shipping_agents')->nullOnDelete();
            $table->decimal('weight_min', 10, 2)->nullable()->after('currency');
            $table->decimal('weight_max', 10, 2)->nullable()->after('weight_min');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipping_rates', function (Blueprint $table) {
            $table->dropForeign(['shipping_agent_id']);
            $table->dropColumn(['shipping_agent_id', 'weight_min', 'weight_max']);
        });
    }
};
