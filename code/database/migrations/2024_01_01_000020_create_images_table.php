<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// TASK 1-B: Create the images table
// Stores metadata about every photo a user takes.
// Note: the actual image file goes to disk (storage/). This table stores info ABOUT it.

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('images', function (Blueprint $table) {
            $table->id();

            // TODO: Foreign key — links this image to the user who took it
            // $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); 


            // ->constrained()      = enforces that user_id must exist in the users table
            // ->cascadeOnDelete()  = if the user is deleted, delete their images too
            

            // --- File info ---
            // file_url       : string(500), required — the URL to access the stored photo
            // file_hash      : char(64), nullable    — SHA-256 hash for exact duplicate detection
            // perceptual_hash: char(64), nullable    — pHash for similar-image detection

            $table->string('file_url', 500);
            $table->char('file_hash', 64);
            $table->char('perceptual_hash', 64);




            // --- EXIF data (extracted from the photo's metadata) ---
            // exif_lat      : decimal(10,8), nullable — GPS latitude  from the photo
            // exif_lng      : decimal(11,8), nullable — GPS longitude from the photo
            // exif_altitude : decimal(8,2),  nullable
            // exif_timestamp: timestamp, nullable     — when the photo was taken
            // exif_device   : string(100), nullable   — e.g. "iPhone 15 Pro"
             
            $table->decimal('exif_lat', 10, 8);
            $table->decimal('exif_lng', 11, 8);
            $table->decimal('exif_altitude', 8, 2);
            $table->timestamp('exif_timestamp') -> nullable();
            $table->string('exif_device', 100) -> nullable(); 


            // --- Dimensions ---
            // width         : unsignedSmallInteger, nullable
            // height        : unsignedSmallInteger, nullable
            // file_size_bytes: unsignedInteger, nullable
            $table->unsignedSmallInteger('width')-> nullable();
            $table->unsignedSmallInteger('height')-> nullable();
            $table->unsignedInteger('file_size_bytes')-> nullable();


            $table->timestamps();

            // TODO: Add index on perceptual_hash for fast similarity lookups
            // $table->index('perceptual_hash');
            $table->index('perceptual_hash');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
