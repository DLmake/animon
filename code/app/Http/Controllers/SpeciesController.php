<?php

namespace App\Http\Controllers;

use App\Models\Species;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

// TASK 3-C: Species controller (the Pokédex)
// Two pages: a searchable/filterable list, and a single species detail page.

class SpeciesController extends Controller
{
    // GET /species — paginated, filterable list of all species
    public function index(Request $request): Response
    {
        // Step 1: Start a query (doesn't hit the DB yet)
        $query = Species::query();

        // Step 2: Apply optional category filter from ?category=bird in the URL
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Step 3: Apply optional search from ?search=robin in the URL
         if ($request->filled('search')) {
             $query->where(function ($q) use ($request) {
                 $q->where('common_name', 'like', "%{$request->search}%")
                   ->orWhere('scientific_name', 'like', "%{$request->search}%");
             });
        }

        // Step 4: Get the IDs of species this user has already caught
        // (used in the Vue page to show caught vs unknown)
        $userSightingIds = Auth::check() ? Auth::user()->sightings()->pluck('species_id')->toArray(): [];

        // Step 5: Paginate — 48 per page, sorted alphabetically
        // ->paginate(48) returns a LengthAwarePaginator with links for the Vue pagination
        $species = $query->orderBy('common_name')->paginate(48);

        return Inertia::render('Species/Index', [$species,$userSightingIds,$request->only(['category','search'])
            // TODO: pass 'species', 'userSightingIds', and 'filters' ($request->only(['category','search']))
        ]);
    }

    // GET /species/{species} — single species detail
    public function show(Species $species): Response
    {
        // $species is auto-loaded from the URL {species} segment (route model binding)

        // TODO:
        // Load sightings with their users:
        $species->load(['sightings.user']);

        // Check if the current user has caught this species:
        $caughtByUser = Auth::check() ? $species->sightings->where('user_id', Auth::id())->isNotEmpty() : false;

        return Inertia::render('Species/Show', [
            'species'     => $species,
            'caughtByUser'=> $caughtByUser,
        ]);
    }
}
