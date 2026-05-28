<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Sighting;
use App\Models\Species;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

// TASK 3-B: Sighting controller
// Handles the full capture flow: show camera page → receive photo → save → redirect

class SightingController extends Controller
{
    // GET /capture — just show the Capture.vue page, no data needed
    public function create(): Response
    {
        // TODO: return Inertia::render('Capture');
    }

    // POST /sightings — called when user submits a photo + species ID
    public function store(Request $request): RedirectResponse
    {
        // Step 1: Validate the incoming data
        // If validation fails, Laravel automatically redirects back with errors
        $request->validate([
            // TODO: add these rules:
            // 'photo'        => 'required|image|max:10240'  (max 10MB image file)
            // 'species_id'   => 'required|exists:species,id' (must be a real species)
            // 'lat'          => 'nullable|numeric|between:-90,90'
            // 'lng'          => 'nullable|numeric|between:-180,180'
            // 'notes'        => 'nullable|string|max:1000'
            // 'ai_confidence'=> 'nullable|numeric|between:0,1'
        ]);

        // Step 2: Save the uploaded photo to disk
        // $file = $request->file('photo');
        // $path = $file->store('sightings', 'public');
        // This saves to storage/app/public/sightings/ and returns the relative path

        // Step 3: Create an Image record in the database
        // $image = Image::create([
        //     'user_id'         => Auth::id(),
        //     'file_url'        => Storage::url($path),  // public URL
        //     'file_hash'       => hash_file('sha256', $file->getRealPath()),
        //     'exif_lat'        => $request->lat,
        //     'exif_lng'        => $request->lng,
        //     'width'           => getimagesize($file->getRealPath())[0] ?? null,
        //     'height'          => getimagesize($file->getRealPath())[1] ?? null,
        //     'file_size_bytes' => $file->getSize(),
        // ]);

        // Step 4: Check if this is the first time ANYONE has sighted this species
        // $isFirstGlobal = Sighting::where('species_id', $request->species_id)->doesntExist();

        // Step 5: Create or update the sighting
        // updateOrCreate(['match on these'] , ['set these values'])
        // If a row with user_id+species_id already exists → UPDATE it
        // If it doesn't exist → INSERT it
        // This enforces the "one Pokédex entry per species" rule
        // $sighting = Sighting::updateOrCreate(
        //     ['user_id' => Auth::id(), 'species_id' => $request->species_id],
        //     [
        //         'image_id'        => $image->id,
        //         'lat'             => $request->lat,
        //         'lng'             => $request->lng,
        //         'sighted_at'      => now(),
        //         'notes'           => $request->notes,
        //         'ai_confidence'   => $request->ai_confidence,
        //         'is_first_global' => $isFirstGlobal,
        //     ]
        // );

        // Step 6: If first global sighting, increment the species counter
        // if ($isFirstGlobal) {
        //     Species::find($request->species_id)->increment('total_sightings');
        // }

        // Step 7: Redirect to dashboard with a flash message
        // return redirect()->route('dashboard')->with('success', 'Added to your collection!');
    }

    // GET /sightings/{sighting} — show one sighting's detail page
    public function show(Sighting $sighting): Response
    {
        // Note: $sighting is automatically fetched from the DB by Laravel (route model binding)
        // You just need to load the relationships before passing to Vue

        // TODO:
        // $sighting->load(['species', 'image', 'user']);
        // return Inertia::render('Sightings/Show', ['sighting' => $sighting]);
    }

    // DELETE /sightings/{sighting}
    public function destroy(Sighting $sighting): RedirectResponse
    {
        // $this->authorize() checks the SightingPolicy — only the owner can delete
        // TODO:
        // $this->authorize('delete', $sighting);
        // $sighting->delete();
        // return redirect()->route('dashboard');
    }
}
