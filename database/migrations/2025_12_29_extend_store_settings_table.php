<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('store_settings', function (Blueprint $table) {
            $table->string('logo')->nullable()->after('store_name');
            $table->text('description')->nullable()->after('logo');
            $table->string('whatsapp')->nullable()->after('phone');
            $table->string('instagram')->nullable()->after('whatsapp');
            $table->string('facebook')->nullable()->after('instagram');

            // Bank information
            $table->string('bank_name')->nullable()->after('facebook');
            $table->string('bank_account_number')->nullable()->after('bank_name');
            $table->string('bank_account_name')->nullable()->after('bank_account_number');

            // Business hours
            $table->string('business_hours')->nullable()->after('bank_account_name');

            // Meta data for SEO
            $table->string('meta_keywords')->nullable()->after('business_hours');
            $table->text('meta_description')->nullable()->after('meta_keywords');
        });
    }

    public function down(): void
    {
        Schema::table('store_settings', function (Blueprint $table) {
            $table->dropColumn([
                'logo',
                'description',
                'whatsapp',
                'instagram',
                'facebook',
                'bank_name',
                'bank_account_number',
                'bank_account_name',
                'business_hours',
                'meta_keywords',
                'meta_description'
            ]);
        });
    }
};
