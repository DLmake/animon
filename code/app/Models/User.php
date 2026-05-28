<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// TASK 2-D: User model
// Extends Authenticatable (not just Model) — that gives it all the
// login/session/password methods Laravel needs for auth.

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        // TODO: Add all user columns:
        // 'name', 'username', 'email', 'password', 'google_id', 'avatar_url', 'bio'
    ];

    // These fields are NEVER included when the model is converted to JSON.
    // Important: password and remember_token must never be sent to the browser.
    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed', // auto-bcrypt when you set $user->password = 'plain'
        ];
    }

    // RELATIONSHIP: A user has many sightings
    // HasMany = the OTHER table has the foreign key (sightings.user_id)
    // $user->sightings  →  SELECT * FROM sightings WHERE user_id = ?
    public function sightings(): HasMany
    {
        // TODO: return $this->hasMany(Sighting::class);
    }

    // RELATIONSHIP: A user has many images
    public function images(): HasMany
    {
        // TODO: return $this->hasMany(Image::class);
    }

    // RELATIONSHIP: A user has many collections
    public function collections(): HasMany
    {
        // TODO: return $this->hasMany(Collection::class);
    }
}
