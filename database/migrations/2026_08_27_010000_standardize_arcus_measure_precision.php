<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('arcus_bows')->update([
            'stick_length' => DB::raw('ROUND(stick_length)'),
            'total_length' => DB::raw('ROUND(total_length)'),
            'stick_weight' => DB::raw('ROUND(stick_weight)'),
            'total_weight' => DB::raw('ROUND(total_weight)'),
            'balance_point' => DB::raw('ROUND(balance_point)'),
            'density' => DB::raw('ROUND(density)'),
            'speed' => DB::raw('ROUND(speed)'),
            'frequency' => DB::raw('ROUND(frequency)'),
            'elasticity' => DB::raw('ROUND(elasticity, 1)'),
            'damping' => DB::raw('ROUND(damping, 3)'),
        ]);

        Schema::table('arcus_bows', function (Blueprint $table) {
            $table->unsignedInteger('stick_length')->nullable()->change();
            $table->unsignedInteger('total_length')->nullable()->change();
            $table->unsignedInteger('stick_weight')->nullable()->change();
            $table->unsignedInteger('total_weight')->nullable()->change();
            $table->unsignedInteger('balance_point')->nullable()->change();
            $table->unsignedInteger('density')->nullable()->change();
            $table->unsignedInteger('speed')->nullable()->change();
            $table->decimal('elasticity', 8, 1)->nullable()->change();
            $table->unsignedInteger('frequency')->nullable()->change();
            $table->decimal('damping', 8, 3)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('arcus_bows', function (Blueprint $table) {
            $table->decimal('stick_length', 8, 2)->nullable()->change();
            $table->decimal('total_length', 8, 2)->nullable()->change();
            $table->decimal('stick_weight', 8, 2)->nullable()->change();
            $table->decimal('total_weight', 8, 2)->nullable()->change();
            $table->decimal('balance_point', 8, 2)->nullable()->change();
            $table->decimal('density', 10, 3)->nullable()->change();
            $table->decimal('speed', 10, 3)->nullable()->change();
            $table->decimal('elasticity', 10, 4)->nullable()->change();
            $table->decimal('frequency', 10, 4)->nullable()->change();
            $table->decimal('damping', 10, 6)->nullable()->change();
        });
    }
};
