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
        Schema::table('payments', function (Blueprint $table) {
            $table->string('snap_token')->nullable()->after('proof');
            $table->string('transaction_id')->nullable()->after('snap_token');
            $table->string('transaction_status')->nullable()->after('transaction_id');
            $table->timestamp('paid_at')->nullable()->after('transaction_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['snap_token', 'transaction_id', 'transaction_status', 'paid_at']);
        });
    }
};
