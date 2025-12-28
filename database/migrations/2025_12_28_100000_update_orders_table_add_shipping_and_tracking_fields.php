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
            // Rename and update existing columns if needed
            if (!Schema::hasColumn('orders', 'order_number')) {
                $table->renameColumn('order_code', 'order_number');
            }
            if (!Schema::hasColumn('orders', 'total_amount')) {
                $table->renameColumn('total_price', 'total_amount');
            }

            // Add new shipping fields
            $table->string('shipping_name')->nullable()->after('total_amount');
            $table->string('shipping_phone', 20)->nullable()->after('shipping_name');
            $table->text('shipping_address')->nullable()->after('shipping_phone');
            $table->string('shipping_city')->nullable()->after('shipping_address');
            $table->string('shipping_postal_code', 10)->nullable()->after('shipping_city');
            $table->decimal('shipping_cost', 10, 2)->default(0)->after('shipping_postal_code');
            $table->string('shipping_method')->nullable()->after('shipping_cost'); // regular, express, same_day

            // Add tracking fields
            $table->string('tracking_number')->nullable()->after('shipping_method');
            $table->string('shipping_courier')->nullable()->after('tracking_number');

            // Add notes
            $table->text('notes')->nullable()->after('shipping_courier');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'shipping_name',
                'shipping_phone',
                'shipping_address',
                'shipping_city',
                'shipping_postal_code',
                'shipping_cost',
                'shipping_method',
                'tracking_number',
                'shipping_courier',
                'notes'
            ]);
        });
    }
};
