<?php

namespace App\Policies;

use App\Models\Sighting;
use App\Models\User;

// TASK 3-F: Sighting policy
// Policies answer the question: "can this user perform this action on this model?"
// Laravel auto-discovers this file and links it to the Sighting model.
// Called by: $this->authorize('delete', $sighting) in SightingController

class SightingPolicy
{
    // Can $user delete $sighting?
    // Rule: only the user who created it can delete it.
    public function delete(User $user, Sighting $sighting): bool
    {
        // TODO: return $user->id === $sighting->user_id;
        // This compares the logged-in user's ID with the sighting's owner ID.
        // If they match → true (allowed). If not → 403 Forbidden.
        return $user->id === $sighting->user_id;
        
    }
}
