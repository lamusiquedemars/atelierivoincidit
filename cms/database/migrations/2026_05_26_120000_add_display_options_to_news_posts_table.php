<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('news_posts', function (Blueprint $table) {
            $table->boolean('is_pinned')->default(false)->after('is_published');
            $table->boolean('has_detail_page')->default(true)->after('is_pinned');
        });
    }

    public function down(): void
    {
        Schema::table('news_posts', function (Blueprint $table) {
            $table->dropColumn(['is_pinned', 'has_detail_page']);
        });
    }
};
