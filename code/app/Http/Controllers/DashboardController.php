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

        //:: = talking to the factory ("give me some sightings")
        //-> = talking to a specific product ("this sighting's species")
        $user = Auth::user();

        // Step 2: Load their sightings, including the related species and image for each
        // Tip: use ->with(['species', 'image']) to eager-load relationships (avoids N+1 queries)
        // Tip: use ->latest('sighted_at') to sort newest first
        // Tip: ->get() executes the query and returns a Collection
        // $sightings = $user->sightings()->with([...])->latest(...)->get();

        //$user->sightings    // property — runs the query immediately
        //user->sightings()  // method call — returns a query builder you can chain onto
        
        $sightings = $user->sightings()->with(['species', 'image']) ->latest('sighted_time') -> get(); 

        /*
        Collection {
        0: Sighting {
            id: 1,
            user_id: 1,
            species_id: 3,
            sighted_at: "2026-01-15",
            notes: "Spotted near the lake",
            
            species: Species {         ← loaded because of with(['species'])
                id: 3,
                common_name: "Robin",
                category: "bird",
                rarity: "common"
            },
            
            image: Image {             ← loaded because of with(['image'])
                id: 7,
                file_url: "..."
            }
        },

        1: Sighting { ... },
        2: Sighting { ... },
}

        */




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
 
    $byRarity   = $sightings->groupBy(fn($s) => $s->species->rarity);
    $byCategory = $sightings->groupBy(fn($s) => $s->species->category);

    $stats = [
        'total'     => $sightings->count(),
        'extinct' => $byRarity->get('extinct', collect())->count(),
        'legendary' => $byRarity->get('legendary', collect())->count(),
        'rare'      => $byRarity->get('rare',      collect())->count(),
        'epic'      => $byRarity->get('epic',      collect())->count(),
        'uncommon'  => $byRarity->get('uncommon',  collect())->count(),
        'animals'   => $byCategory->get('mammal',  collect())->count() 
                    + $byCategory->get('bird',    collect())->count() 
                    + $byCategory->get('reptile',    collect())->count()
                    + $byCategory->get('amphibian',    collect())->count() 
                    + $byCategory->get('fish',    collect())->count(),

        'plants'    => $byCategory->get('plant',   collect())->count(),
        'insects'   => $byCategory->get('insect',  collect())->count(),
        'fungi'    => $byCategory->get('fungi',   collect())->count(),
    ];





        // Step 4: Return the Vue page with data as props
        // Inertia::render('PageName', ['propName' => $value])
        // The 'Dashboard' string maps to resources/js/Pages/Dashboard.vue
        return Inertia::render('Dashboard', [
            'stats' => $stats,
            'sightings' => $sightings
            // TODO: pass 'sightings' and 'stats'
        ]);
    }
}
