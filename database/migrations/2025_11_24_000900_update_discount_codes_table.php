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
        Schema::table('discount_codes', function (Blueprint $table) {
            $table->string('description')->nullable()->after('code');
            $table->string('currency', 3)->nullable()->after('type');
            $table->timestamp('starts_at')->nullable()->after('region');
            $table->timestamp('expires_at')->nullable()->after('starts_at');
            $table->unsignedInteger('max_redemptions')->nullable()->after('expires_at');
            $table->unsignedInteger('max_redemptions_per_user')->nullable()->after('max_redemptions');
            $table->boolean('once_per_email')->default(false)->after('max_redemptions_per_user');
            $table->json('allowed_emails')->nullable()->after('once_per_email');
            $table->unsignedInteger('usage_count')->default(0)->after('allowed_emails');
            $table->string('stripe_coupon_id')->nullable()->after('usage_count');
            $table->string('stripe_promotion_code_id')->nullable()->after('stripe_coupon_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('discount_codes', function (Blueprint $table) {
            $table->dropColumn([
                'description',
                'currency',
                'starts_at',
                'expires_at',
                'max_redemptions',
                'max_redemptions_per_user',
                'once_per_email',
                'allowed_emails',
                'usage_count',
                'stripe_coupon_id',
                'stripe_promotion_code_id',
            ]);
        });
    }
};
