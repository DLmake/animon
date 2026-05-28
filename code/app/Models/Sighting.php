<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

// TASK 2-C: Sighting model
// The most important model — the user's Pokédex entry.
// It connects a User + Species + Image together in one row.

class Sighting extends Model
{
    protected $fillable = [
        // TODO: List every column from your sightings migration.
        // Include: user_id, species_id, image_id, lat, lng, altitude,
        //          location_name, country_code, sighted_at,
        //          notes, ai_confidence, is_first_global
    ];

    protected function casts(): array
    {
        return [
            // TODO:
            // 'sighted_at'     => 'datetime',
            // 'is_first_global'=> 'boolean',
            // 'lat'            => 'float',
            // 'lng'            => 'float',
            // 'ai_confidence'  => 'float',
        ];
    }

    // RELATIONSHIP: A sighting belongs to a user
    // BelongsTo = this table has the foreign key (user_id)
    public function user(): BelongsTo
    {
        // TODO: return $this->belongsTo(User::class);
    }

    // RELATIONSHIP: A sighting belongs to a species
    public function species(): BelongsTo
    {
        // TODO: return $this->belongsTo(Species::class);
    }

    // RELATIONSHIP: A sighting belongs to an image
    public function image(): BelongsTo
    {
        // TODO: return $this->belongsTo(Image::class);
    }

    // RELATIONSHIP: A sighting can belong to many collections (via pivot table)
    // BelongsToMany = many-to-many, needs a pivot table
    // $sighting->collections  →  joins through collection_sightings
    public function collections(): BelongsToMany
    {
        // TODO: return $this->belongsToMany(Collection::class, 'collection_sightings')
        //                    ->withPivot('added_at');
        // 'collection_sightings' is the pivot table name
        // withPivot('added_at') lets you access $sighting->collections->pivot->added_at
    }
}
