<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('store_settings', function (Blueprint $table) {
            // Payment Gateway Settings
            $table->boolean('midtrans_enabled')->default(false);
            $table->string('midtrans_server_key')->nullable();
            $table->string('midtrans_client_key')->nullable();
            $table->boolean('midtrans_is_production')->default(false);

            $table->boolean('bank_transfer_enabled')->default(true);
            $table->boolean('e_wallet_enabled')->default(false);
            $table->boolean('cod_enabled')->default(true);

            // Shipping Settings
            $table->integer('shipping_regular_cost')->default(15000);
            $table->string('shipping_regular_name')->default('Regular');
            $table->string('shipping_regular_estimation')->default('3-5 hari');

            $table->integer('shipping_express_cost')->default(30000);
            $table->string('shipping_express_name')->default('Express');
            $table->string('shipping_express_estimation')->default('1-2 hari');

            $table->integer('shipping_sameday_cost')->default(50000);
            $table->string('shipping_sameday_name')->default('Same Day');
            $table->string('shipping_sameday_estimation')->default('1 hari');

            $table->boolean('free_shipping_enabled')->default(false);
            $table->integer('free_shipping_minimum')->default(500000);
        });
    }

    public function down(): void
    {
        Schema::table('store_settings', function (Blueprint $table) {
            $table->dropColumn([
                'midtrans_enabled',
                'midtrans_server_key',
                'midtrans_client_key',
                'midtrans_is_production',
                'bank_transfer_enabled',
                'e_wallet_enabled',
                'cod_enabled',
                'shipping_regular_cost',
                'shipping_regular_name',
                'shipping_regular_estimation',
                'shipping_express_cost',
                'shipping_express_name',
                'shipping_express_estimation',
                'shipping_sameday_cost',
                'shipping_sameday_name',
                'shipping_sameday_estimation',
                'free_shipping_enabled',
                'free_shipping_minimum'
            ]);
        });
    }
};
