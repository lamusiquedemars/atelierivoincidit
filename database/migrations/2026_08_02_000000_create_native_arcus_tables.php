<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('arcus_terms', function (Blueprint $table) {
            $table->id();
            $table->string('type', 40)->index();
            $table->unsignedBigInteger('legacy_id')->nullable();
            $table->string('name');
            $table->string('group', 60)->nullable()->index();
            $table->string('slug')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->unique(['type', 'legacy_id']);
        });

        Schema::create('arcus_bows', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('legacy_id')->nullable()->unique();
            $table->string('code', 40)->unique();
            $table->string('name', 100)->nullable();
            $table->string('status', 30)->default('available')->index();
            $table->unsignedInteger('price')->nullable();
            $table->unsignedTinyInteger('discount')->nullable();
            $table->boolean('active')->default(true)->index();

            foreach ([
                'range', 'instrument', 'style', 'shape', 'size', 'wood', 'origin', 'color',
                'button_material', 'frog_material', 'slide_material', 'tip_material', 'garnish',
                'flexibility', 'responsiveness', 'handling', 'natural_pressure', 'tone',
                'projection', 'sustain', 'articulation',
            ] as $relation) {
                $table->foreignId($relation.'_id')->nullable()->constrained('arcus_terms')->nullOnDelete();
            }

            $table->unsignedInteger('stick_length')->nullable();
            $table->unsignedInteger('total_length')->nullable();
            $table->unsignedInteger('stick_weight')->nullable();
            $table->unsignedInteger('total_weight')->nullable();
            $table->unsignedInteger('balance_point')->nullable();
            $table->unsignedInteger('density')->nullable();
            $table->unsignedInteger('speed')->nullable();
            $table->decimal('elasticity', 8, 1)->nullable();
            $table->unsignedInteger('frequency')->nullable();
            $table->decimal('damping', 8, 3)->nullable();
            $table->text('short_trait')->nullable();
            $table->longText('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('arcus_bow_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('arcus_bow_id')->constrained('arcus_bows')->cascadeOnDelete();
            $table->foreignId('media_asset_id')->constrained('media_assets')->restrictOnDelete();
            $table->unsignedInteger('position')->default(0);
            $table->text('caption')->nullable();
            $table->timestamps();

            $table->unique(['arcus_bow_id', 'media_asset_id']);
            $table->index(['arcus_bow_id', 'position']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('arcus_bow_media');
        Schema::dropIfExists('arcus_bows');
        Schema::dropIfExists('arcus_terms');
    }
};
