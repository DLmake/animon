<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

// TASK 2-B: Image model
// Represents one row in the images table.
// An image belongs to a user and (after it's used in a capture) has one sighting.

class Image extends Model
{
    protected $fillable = [
        // TODO: List every column from your images migration.
        // Include: user_id, file_url, file_hash, perceptual_hash,
        //          exif_lat, exif_lng, exif_altitude, exif_timestamp,
        //          exif_device, width, height, file_size_bytes
        'user_id',
        'file_url',
        'file_hash',
        'perceptual_hash',
        'exif_lat',
        'exif_lng',
        'exif_altitude',
        'exif_timestamp',
        'exif_device',
        'width',
        'height',
        'file_size_bytes'





    ];

    protected function casts(): array
    {
        return [
            // TODO:
            // 'exif_timestamp' => 'datetime',
            // 'exif_lat'       => 'float',
            // 'exif_lng'       => 'float',
            'exif_timestamp' => 'datetime',
            'exif_lat'=> 'float',
            'exif_lng'=> 'float',
        ];
    }

    // RELATIONSHIP: An image belongs to a user
    // $image->user  →  SELECT * FROM users WHERE id = images.user_id
    public function user(): BelongsTo
        
    {
        // TODO: return $this->belongsTo(User::class);
        return $this->belongsTo(User::class);
    }

    // RELATIONSHIP: An image has one sighting (after it's been identified)
    // $image->sighting  →  SELECT * FROM sightings WHERE image_id = ?
    public function sighting(): HasOne
    {
        return $this->HasOne(Sighting::class);
        // TODO: return $this->hasOne(Sighting::class);
    }
}
