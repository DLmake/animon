<?php

namespace App\Http\Controllers;

use App\Models\Sighting;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

// TASK 3-D: Map controller
// Two methods:
//   index() — returns the Map.vue page (no data, the map fetches its own pins)
//   pins()  — returns JSON for the map markers (called by Map.vue via fetch())

class MapController extends Controller
{
    // GET /map — just render the map page shell
    public function index(): Response
    {
         return Inertia::render('Map');
    }

    // GET /map/pins — returns JSON array of sightings with coordinates
    // This is a regular JSON endpoint, NOT an Inertia response
    // Map.vue calls this with fetch() after the map has mounted
    public function pins(): JsonResponse
    {
        // Step 1: Query sightings that have coordinates
        // $query = Sighting::with('species:id,common_name,category,rarity,reference_image')
        //     ->whereNotNull('lat')
        //     ->whereNotNull('lng')
        //     ->select('id', 'species_id', 'user_id', 'lat', 'lng', 'location_name', 'sighted_at');

        //what this means?
        /*
        FROM sightings
            give me: id, species_id, lat, lng, location_name, sighted_at
        JOIN species (where species.id = sighting.species_id)
            give me: id, common_name, category, rarity

        */
        $query = Sighting::with('species:id,common_name,category,rarity,refrence_image') -> whereNotNull('lat') -> whereNotNull('lng') 
            ->select('id', 'species_id', 'user_id', 'lat', 'lng', 'location_name', 'sighted_at');


        // Step 2: If logged in, only show this user's pins
        if (Auth::check()) {
            $query->where('user_id', Auth::id());
        }


        // Step 3: Return as JSON (response()->json() instead of Inertia::render())
        return response()->json($query->get());
    }
}
