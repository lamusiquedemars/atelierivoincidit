<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('media_assets', 'thumbnail_path')) {
            return;
        }

        Schema::table('media_assets', function (Blueprint $table): void {
            $table->string('thumbnail_path')->nullable()->after('path');
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('media_assets', 'thumbnail_path')) {
            return;
        }

        Schema::table('media_assets', function (Blueprint $table): void {
            $table->dropColumn('thumbnail_path');
        });
    }
};
