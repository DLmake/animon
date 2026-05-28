<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

// TASK 3-A: Dashboard controller
// This runs when a logged-in user visits /dashboard.
// Goal: load all of this user's sightings + calculate stats, send to Dashboard.vue

class DashboardController extends Controller
{
    public function index(): Response
    {
        // Step 1: Get the logged-in user
        // $user = Auth::user();

        // Step 2: Load their sightings, including the related species and image for each
        // Tip: use ->with(['species', 'image']) to eager-load relationships (avoids N+1 queries)
        // Tip: use ->latest('sighted_at') to sort newest first
        // Tip: ->get() executes the query and returns a Collection
        // $sightings = $user->sightings()->with([...])->latest(...)->get();

        // Step 3: Calculate stats by filtering the $sightings collection
        // Tip: $sightings->count()  gives total
        // Tip: $sightings->filter(fn($s) => $s->species->category === 'bird')->count()
        // $stats = [
        //     'total'      => ...,
        //     'animals'    => ...,  // category in: animal, bird, reptile, amphibian, fish
        //     'insects'    => ...,  // category in: insect, arachnid
        //     'plants'     => ...,  // category in: plant, fungi
        //     'legendaries'=> ...,  // rarity === 'legendary'
        // ];

        // Step 4: Return the Vue page with data as props
        // Inertia::render('PageName', ['propName' => $value])
        // The 'Dashboard' string maps to resources/js/Pages/Dashboard.vue
        return Inertia::render('Dashboard', [
            // TODO: pass 'sightings' and 'stats'
        ]);
    }
}
