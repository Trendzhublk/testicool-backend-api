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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('tracking_number')->nullable()->unique()->after('id');
            $table->enum('status', ['pending', 'processing', 'shipped', 'delivered', 'cancelled'])
                ->default('pending')
                ->after('line_total');
            $table->text('status_note')->nullable()->after('status');
            $table->foreignId('shipping_agent_id')->nullable()->after('status_note')->constrained('shipping_agents')->nullOnDelete();
            $table->foreignId('sales_agent_id')->nullable()->after('shipping_agent_id')->constrained('users')->nullOnDelete();
            $table->string('customer_email')->nullable()->after('sales_agent_id');
            $table->string('customer_name')->nullable()->after('customer_email');
            $table->timestamp('status_updated_at')->nullable()->after('customer_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['shipping_agent_id']);
            $table->dropForeign(['sales_agent_id']);
            $table->dropColumn([
                'tracking_number',
                'status',
                'status_note',
                'shipping_agent_id',
                'sales_agent_id',
                'customer_email',
                'customer_name',
                'status_updated_at',
            ]);
        });
    }
};
