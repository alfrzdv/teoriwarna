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
            $table->boolean('midtrans_enabled')->default(false)->after('meta_description');
            $table->string('midtrans_server_key')->nullable()->after('midtrans_enabled');
            $table->string('midtrans_client_key')->nullable()->after('midtrans_server_key');
            $table->boolean('midtrans_is_production')->default(false)->after('midtrans_client_key');

            $table->boolean('bank_transfer_enabled')->default(true)->after('midtrans_is_production');
            $table->boolean('e_wallet_enabled')->default(false)->after('bank_transfer_enabled');
            $table->boolean('cod_enabled')->default(true)->after('e_wallet_enabled');

            // Shipping Settings
            $table->integer('shipping_regular_cost')->default(15000)->after('cod_enabled');
            $table->string('shipping_regular_name')->default('Regular')->after('shipping_regular_cost');
            $table->string('shipping_regular_estimation')->default('3-5 hari')->after('shipping_regular_name');

            $table->integer('shipping_express_cost')->default(30000)->after('shipping_regular_estimation');
            $table->string('shipping_express_name')->default('Express')->after('shipping_express_cost');
            $table->string('shipping_express_estimation')->default('1-2 hari')->after('shipping_express_name');

            $table->integer('shipping_sameday_cost')->default(50000)->after('shipping_express_estimation');
            $table->string('shipping_sameday_name')->default('Same Day')->after('shipping_sameday_cost');
            $table->string('shipping_sameday_estimation')->default('1 hari')->after('shipping_sameday_name');

            $table->boolean('free_shipping_enabled')->default(false)->after('shipping_sameday_estimation');
            $table->integer('free_shipping_minimum')->default(500000)->after('free_shipping_enabled');
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
