<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// TASK 1-C: Create the sightings table
// This is the CORE table — it's the user's Pokédex entry.
// One row = one user has caught one species (with a photo, location, date).
// A user can only have ONE sighting per species (enforced by the unique constraint below).

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sightings', function (Blueprint $table) {
            $table->id();

            // TODO: Three foreign keys — who caught it, what they caught, which photo
            // $table->foreignId('user_id')   ->constrained()->cascadeOnDelete();
            // $table->foreignId('species_id')->constrained()->cascadeOnDelete();
            // $table->foreignId('image_id')  ->constrained()->cascadeOnDelete();

            $table->foreignId('user_id')   ->constrained()->cascadeOnDelete();
            $table->foreignId('species_id')->constrained()->cascadeOnDelete();
            $table->foreignId('image_id')  ->constrained()->cascadeOnDelete();

            // --- Where ---
            // lat          : decimal(10,8), nullable — final latitude  (may differ from EXIF if corrected)
            // lng          : decimal(11,8), nullable — final longitude
            // altitude     : decimal(8,2),  nullable
            // location_name: string(200), nullable   — human-readable, e.g. "Central Park, New York"
            // country_code : char(2), nullable       — e.g. "US", "CA"
            $table->string('location_name',200)  ->nullable();
            $table->decimal('lat',10,8)  ->nullable();
            $table->decimal('lng',11,8)  ->nullable();
            $table->decimal('altitude',8,2)  ->nullable();
            $table->char('country_code',2)  ->nullable();

            // --- When ---
            // sighted_at: timestamp, required        — when the user saw the creature
            $table->timestamp('sighted_time');


            // --- Notes & AI ---
            // notes         : text, nullable
            // ai_confidence : decimal(5,4), nullable — e.g. 0.9400 means 94%
            // is_first_global: boolean, default false — true if this is the FIRST time anyone logged this species
            $table->text('notes')->nullable(); 
            $table->decimal('ai_confidence',5,4)->nullable();
            $table->boolean('is_first_sighting')->default(false);
            


            $table->timestamps();

            // TODO: The most important constraint — one Pokédex entry per species per user:
            // $table->unique(['user_id', 'species_id']);
            $table->unique(['user_id', 'species_id']);

            // TODO: Indexes for map queries and filtering:
            // $table->index(['lat', 'lng']);
            // $table->index('user_id');
            $table->index(['lat', 'lng']);
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sightings');
    }
};
