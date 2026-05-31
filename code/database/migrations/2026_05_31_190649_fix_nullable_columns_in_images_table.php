<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('images', function (Blueprint $table) {
            $table->char('file_hash', 64)->nullable()->change();
            $table->char('perceptual_hash', 64)->nullable()->change();
            $table->decimal('exif_lat', 10, 8)->nullable()->change();
            $table->decimal('exif_lng', 11, 8)->nullable()->change();
            $table->decimal('exif_altitude', 8, 2)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('images', function (Blueprint $table) {
            $table->char('file_hash', 64)->nullable(false)->change();
            $table->char('perceptual_hash', 64)->nullable(false)->change();
            $table->decimal('exif_lat', 10, 8)->nullable(false)->change();
            $table->decimal('exif_lng', 11, 8)->nullable(false)->change();
            $table->decimal('exif_altitude', 8, 2)->nullable(false)->change();
        });
    }
};
