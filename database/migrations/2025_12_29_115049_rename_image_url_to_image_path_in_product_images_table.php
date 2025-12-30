<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Skip: image_path already exists in initial migration
        if (Schema::hasColumn("product_images", "image_url")) {
            Schema::table("product_images", function (Blueprint $table) {
                $table->renameColumn("image_url", "image_path");
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn("product_images", "image_path")) {
            Schema::table("product_images", function (Blueprint $table) {
                $table->renameColumn("image_path", "image_url");
            });
        }
    }
};
