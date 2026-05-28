<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// TASK 1-D: Create collections + pivot table
// Users can group their sightings into named collections, e.g. "Backyard Birds 2026"
// A sighting can belong to many collections, a collection can have many sightings.
// That's a many-to-many relationship — it needs a PIVOT TABLE (collection_sightings).

return new class extends Migration
{
    public function up(): void
    {
        // --- Main collections table ---
        Schema::create('collections', function (Blueprint $table) {
            $table->id();

            // TODO:
            // user_id    : foreignId, constrained, cascadeOnDelete
            // name       : string(100), required
            // description: text, nullable
            // is_public  : boolean, default true

            $table->timestamps();
        });

        // --- Pivot table (joins sightings ↔ collections) ---
        Schema::create('collection_sightings', function (Blueprint $table) {
            // TODO:
            // collection_id: foreignId, constrained, cascadeOnDelete
            // sighting_id  : foreignId, constrained, cascadeOnDelete
            // added_at     : timestamp, useCurrent()

            // TODO: Composite primary key (prevents duplicate entries):
            // $table->primary(['collection_id', 'sighting_id']);

            // Note: No $table->timestamps() needed on pivot tables
        });
    }

    public function down(): void
    {
        // Drop in reverse order — pivot first, then the tables it references
        Schema::dropIfExists('collection_sightings');
        Schema::dropIfExists('collections');
    }
};
