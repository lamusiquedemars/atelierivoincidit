<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->whenTableExists('pages', function (Blueprint $table): void {
            $table->foreignId('hero_media_id')->nullable()->after('hero_image_path')->constrained('media_assets')->nullOnDelete();
        });

        $this->whenTableExists('news_posts', function (Blueprint $table): void {
            $table->foreignId('image_media_id')->nullable()->after('image_path')->constrained('media_assets')->nullOnDelete();
        });

        $this->whenTableExists('articles', function (Blueprint $table): void {
            $table->foreignId('image_media_id')->nullable()->after('image_path')->constrained('media_assets')->nullOnDelete();
        });

        $this->whenTableExists('events', function (Blueprint $table): void {
            $table->foreignId('image_media_id')->nullable()->after('image_path')->constrained('media_assets')->nullOnDelete();
        });

        $this->whenTableExists('gallery_images', function (Blueprint $table): void {
            $table->foreignId('media_asset_id')->nullable()->after('image_path')->constrained('media_assets')->nullOnDelete();
        });

        $this->whenTableExists('site_settings', function (Blueprint $table): void {
            $table->foreignId('logo_media_id')->nullable()->after('logo_path')->constrained('media_assets')->nullOnDelete();
            $table->foreignId('favicon_media_id')->nullable()->after('favicon_path')->constrained('media_assets')->nullOnDelete();
            $table->foreignId('default_og_media_id')->nullable()->after('default_og_image_path')->constrained('media_assets')->nullOnDelete();
        });
    }

    public function down(): void
    {
        $this->whenTableExists('site_settings', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('default_og_media_id');
            $table->dropConstrainedForeignId('favicon_media_id');
            $table->dropConstrainedForeignId('logo_media_id');
        });

        $this->whenTableExists('gallery_images', fn (Blueprint $table) => $table->dropConstrainedForeignId('media_asset_id'));
        $this->whenTableExists('events', fn (Blueprint $table) => $table->dropConstrainedForeignId('image_media_id'));
        $this->whenTableExists('articles', fn (Blueprint $table) => $table->dropConstrainedForeignId('image_media_id'));
        $this->whenTableExists('news_posts', fn (Blueprint $table) => $table->dropConstrainedForeignId('image_media_id'));
        $this->whenTableExists('pages', fn (Blueprint $table) => $table->dropConstrainedForeignId('hero_media_id'));
    }

    private function whenTableExists(string $table, callable $callback): void
    {
        if (Schema::hasTable($table)) {
            Schema::table($table, $callback);
        }
    }
};
