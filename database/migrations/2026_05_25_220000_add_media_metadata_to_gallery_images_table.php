<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gallery_images', function (Blueprint $table) {
            $table->string('alt_text')->nullable()->after('image_path');
            $table->string('credit')->nullable()->after('caption');
            $table->unsignedInteger('width')->nullable()->after('alt_text');
            $table->unsignedInteger('height')->nullable()->after('width');
        });
    }

    public function down(): void
    {
        Schema::table('gallery_images', function (Blueprint $table) {
            $table->dropColumn([
                'alt_text',
                'credit',
                'width',
                'height',
            ]);
        });
    }
};
