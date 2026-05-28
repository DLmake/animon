<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

// TASK 2-E: Collection model
// A named group of sightings a user creates, e.g. "Backyard Birds 2026".

class Collection extends Model
{
    protected $fillable = [
        // TODO: 'user_id', 'name', 'description', 'is_public'
    ];

    protected function casts(): array
    {
        return [
            // TODO: 'is_public' => 'boolean',
        ];
    }

    // RELATIONSHIP: A collection belongs to a user
    public function user(): BelongsTo
    {
        // TODO: return $this->belongsTo(User::class);
    }

    // RELATIONSHIP: A collection has many sightings (via pivot)
    // This is the mirror of Sighting::collections()
    public function sightings(): BelongsToMany
    {
        // TODO: return $this->belongsToMany(Sighting::class, 'collection_sightings')
        //                    ->withPivot('added_at');
    }
}
