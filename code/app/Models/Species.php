<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

// TASK 2-A: Species model
// A model is a PHP class that represents one row in a database table.
// Laravel maps App\Models\Species → the 'species' table automatically (snake_case plural).

class Species extends Model
{
    // $fillable is a security whitelist.
    // Only columns listed here can be mass-assigned (e.g. Species::create($data)).
    // This prevents users from injecting unexpected fields.
    protected $fillable = [
        // TODO: List every column from your species migration here as a string.
        // Example:
        // 'common_name',
        // 'scientific_name',
        // 'category',
        // ... etc
    ];

    // casts() tells Laravel how to convert database values to PHP types automatically.
    // Without casts, everything comes back as a plain string.
    protected function casts(): array
    {
        return [
            // TODO: Add these two casts:
            // 'ai_tags'      => 'array',    — stored as JSON string, returned as PHP array
            // 'first_seen_at'=> 'datetime', — returned as a Carbon date object
        ];
    }

    // RELATIONSHIP: One species has many sightings
    // $species->sightings  →  runs: SELECT * FROM sightings WHERE species_id = ?
    public function sightings(): HasMany
    {
        // TODO: return $this->hasMany(Sighting::class);
    }

    // HELPER METHOD: Returns a Tailwind CSS class based on rarity
    // Used in Vue templates: <span :class="species.rarity_color">
    // Note: This is sent to Vue via the model's JSON output
    public function rarityColor(): string
    {
        // TODO: Use a match expression to return a CSS class string:
        // legendary → 'text-yellow-400'
        // rare      → 'text-blue-400'
        // uncommon  → 'text-green-400'
        // default   → 'text-gray-400'
    }
}
