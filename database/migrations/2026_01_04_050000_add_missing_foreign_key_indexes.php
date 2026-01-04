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
        // Add missing index on carts.user_id
        Schema::table('carts', function (Blueprint $table) {
            $table->index('user_id');
        });

        // Add missing index on user_addresses.user_id
        Schema::table('user_addresses', function (Blueprint $table) {
            $table->index('user_id');
        });

        // Add missing index on orders.address_id
        Schema::table('orders', function (Blueprint $table) {
            $table->index('address_id');
        });

        // Add missing index on payments.order_id
        Schema::table('payments', function (Blueprint $table) {
            $table->index('order_id');
        });

        // Add missing indexes on refunds table
        if (Schema::hasTable('refunds')) {
            Schema::table('refunds', function (Blueprint $table) {
                $table->index('order_id');
                $table->index('user_id');
                $table->index('approved_by');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
        });

        Schema::table('user_addresses', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['address_id']);
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex(['order_id']);
        });

        if (Schema::hasTable('refunds')) {
            Schema::table('refunds', function (Blueprint $table) {
                $table->dropIndex(['order_id']);
                $table->dropIndex(['user_id']);
                $table->dropIndex(['approved_by']);
            });
        }
    }
};
