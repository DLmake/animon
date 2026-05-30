<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// TASK 1-A: Create the species table
// This is the "Pokédex" — every known species lives here.
// Migrations are like a version-controlled history of your database schema.
// Run with: php artisan migrate

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('species', function (Blueprint $table) {
            // TODO: Add these columns one by one.
            // Tip: $table->string('column_name') for text
            //       $table->text('column_name') for long text
            //       $table->enum('column_name', ['option1','option2']) for fixed choices
            //       $table->boolean() for true/false
            //       $table->unsignedInteger() for positive whole numbers
            //       $table->json() to store an array/object as a JSON string
            //       $table->timestamp() for a date+time value
            //       ->nullable() at the end means the column is optional


            $table->id(); // always first — auto-incrementing primary key


            // --- Basic identity ---
            // common_name   : string, required (e.g. "American Robin")
            // scientific_name: string, nullable (e.g. "Turdus migratorius")
            $table->string('common_name') ;
            $table->string('scientific_name')->nullable();


            // --- Classification ---
            // category: enum, required
            //   allowed values: 'animal','bird','reptile','amphibian','insect','arachnid','plant','fungi','fish'

            $table->enum('category',['mammal','bird','reptile','amphibian','insect','arachnid','plant','fungi','fish']);


            // --- Taxonomy (all nullable strings) ---
            // kingdom, phylum, class, order_name, family, genus
            $table->string('kingdom')->nullable();
            $table->string('phylum')->nullable();
            $table->string('class')->nullable();
            $table->string('order_name')->nullable();
            $table->string('family')->nullable();
            $table->string('genus')->nullable();

            // --- Description ---
            // description: long text, nullable
            // rarity: enum — 'common','uncommon','rare','legendary' — default 'common'
            // habitat: string, nullable
            $table->text('description')->nullable();
            $table->enum('rarity', ['common','uncommon','rare','epic','legendary','extinct']) -> default('common');
            $table->string('habitat')->nullable();


            // --- Media & AI ---
            // reference_image: string (URL), nullable
            // ai_provider_id: string, nullable  ← ID from iNaturalist/Google Vision
            // ai_tags: json, nullable            ← raw AI response labels

            $table->string('refrence_image')->nullable();
            $table->string('ai_provider_id')->nullable();
            $table->json('ai_tags')->nullable();


            // --- Stats ---
            // total_sightings: unsignedInteger, default 0
            // first_seen_at: timestamp, nullable
            $table->unsignedInteger('total_sightings') -> default(0);

            $table->timestamps(); // always last — adds created_at and updated_at
            //added three indexes instead of one
            $table->index('rarity');
            $table->index('scientific_name');
            $table->index('category');


            // TODO: Add two indexes for faster searching:
            // $table->index('category');
            // $table->index('scientific_name');
        });
    }

    public function down(): void
    {
        // This runs on php artisan migrate:rollback — undo the up() method
        Schema::dropIfExists('species');
    }
};
