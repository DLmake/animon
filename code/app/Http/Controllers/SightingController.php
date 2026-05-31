<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Sighting;
use App\Models\Species;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
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
        return Inertia::render('Capture');
    }

    // POST /sightings — called when user submits a photo + species ID
    public function store(Request $request): RedirectResponse
    {
        // Step 1: Validate the incoming data
        // If validation fails, Laravel automatically redirects back with errors

        /*
        User opens Capture page
        → Browser asks "allow location?" 
        → User accepts
        → Vue stores lat/lng
        → User takes/uploads photo
        → Vue sends photo + lat + lng together
        → Laravel receives all of it in $request

        */
        $request->validate([
            'photo'=> ['required', 'image', 'mimes:jpg,jpeg,png,webp,heic', 'max:10240'],
            'species_id'=> ['required', 'exists:species,id'],
            'lat' => ['nullable', 'numeric', 'between:-90,90'],
            'lng' => ['nullable', 'numeric', 'between:-180,180'],
            'notes'=> ['nullable', 'string', 'max:1000'],
            'ai_confidence' => ['nullable', 'numeric', 'between:0,1'],
        ]);

        // Step 2: Save the uploaded photo to disk
        // $file = $request->file('photo');
        // $path = $file->store('sightings', 'public');
        // This saves to storage/app/public/sightings/ and returns the relative path

        $file = $request->file('photo');
        $path = $file->store('sightings', 'public');

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
        $image = Image::create([
            'user_id' => Auth::id(),
            'file_url' => Storage::url($path),
            'file_hash' => hash_file('sha256', $file -> getRealPath()),
            //its browser location not the actual exif data... so...
            'exif_lat' => $request ->lat,
            'exif_lng' => $request -> lng,
            'width' => getImageSize($file->getRealPath())[0] ?? null,
            'height'=> getimagesize($file->getRealPath())[1] ?? null,
            'file_size_bytes' => $file->getSize(),







        ]);

        // Step 4: Check if this is the first time ANYONE has sighted this species
        // $isFirstGlobal = Sighting::where('species_id', $request->species_id)->doesntExist();
        $isFirstGlobal = Sighting::where('species_id', $request->species_id)->doesntExist();


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
        $sighting = Sighting::updateOrCreate(
            ['user_id' => Auth::id(), 'species_id' => $request -> species_id],

        
            [
                'image_id'        => $image->id,
                'lat'             => $request->lat,
                'lng'             => $request->lng,
                'sighted_time'     => now(),
                'notes'           => $request->notes,
                'ai_confidence'   => $request->ai_confidence,
                'is_first_sighting' => $isFirstGlobal,
            ]
        );

        // Step 6: If first global sighting, increment the species counter
        // if ($isFirstGlobal) {
        //     Species::find($request->species_id)->increment('total_sightings');
        // }

        if($isFirstGlobal){
            Species::find($request -> species_id) -> increment('total_sightings');

        }

        // Step 7: Redirect to dashboard with a flash message
        // return redirect()->route('dashboard')->with('success', 'Added to your collection!');
        return redirect()->route('dashboard')->with('success', 'Added to your collection!');
    }

    // GET /sightings/{sighting} — show one sighting's detail page
    public function show(Sighting $sighting): Response
    {
        // Note: $sighting is automatically fetched from the DB by Laravel (route model binding)
        // You just need to load the relationships before passing to Vue

        // TODO:
        // $sighting->load(['species', 'image', 'user']);
        // return Inertia::render('Sightings/Show', ['sighting' => $sighting]);
        $sighting -> load(['species', 'image', 'user']);
        return Inertia::render('Sightings/Show', ['sighting' => $sighting]);
    }

    // POST /identify — send photo to iNaturalist vision API, return species suggestion
    public function identify(Request $request): JsonResponse
    {
        $request->validate(['photo' => 'required|image|mimes:jpg,jpeg,png,webp,heic|max:10240']);

        $file = $request->file('photo');

        // Build the request to iNaturalist
        $pending = Http::timeout(15)
            ->attach('image', file_get_contents($file->getRealPath()), 'photo.jpg');

        // Attach token if configured
        $token = config('services.inaturalist.token');
        if ($token) {
            $pending = $pending->withToken($token);
        }

        // Send lat/lng if available — improves accuracy significantly
        $params = [];
        if ($request->lat) $params['lat'] = $request->lat;
        if ($request->lng) $params['lng'] = $request->lng;

        $response = $pending->post('https://api.inaturalist.org/v1/computervision/score_image', $params);

        if (!$response->successful()) {
            return response()->json(['error' => 'iNaturalist API error: ' . $response->status()], 422);
        }

        $top = $response->json('results.0');

        if (!$top) {
            return response()->json(['error' => 'Could not identify species'], 422);
        }

        $taxon = $top['taxon'];

        // Map iNaturalist iconic_taxon_name to our category enum
        $categoryMap = [
            'Aves'        => 'bird',
            'Mammalia'    => 'mammal',
            'Reptilia'    => 'reptile',
            'Amphibia'    => 'amphibian',
            'Actinopterygii' => 'fish',
            'Insecta'     => 'insect',
            'Arachnida'   => 'arachnid',
            'Plantae'     => 'plant',
            'Fungi'       => 'fungi',
        ];
        $category = $categoryMap[$taxon['iconic_taxon_name'] ?? ''] ?? 'mammal';

        // Try to find the species in our database — match by scientific name or iNaturalist ID
        $species = Species::where('scientific_name', $taxon['name'])
            ->orWhere('ai_provider_id', (string) $taxon['id'])
            ->first();

        // If not found, create a new entry so species_id is always populated
        if (!$species) {
            $species = Species::create([
                'common_name'     => $taxon['preferred_common_name'] ?? $taxon['name'],
                'scientific_name' => $taxon['name'],
                'category'        => $category,
                'rarity'          => 'common',
                'ai_provider_id'  => (string) $taxon['id'],
            ]);
        }

        // Confidence: use combined_score (0-100) divided by 100, or vision_score normalised
        $confidence = isset($top['combined_score'])
            ? round($top['combined_score']) / 100
            : round($top['vision_score'] / 20, 2);
        $confidence = min(1, max(0, $confidence));

        return response()->json([
            'species_id'      => $species?->id,
            'common_name'     => $taxon['preferred_common_name'] ?? $taxon['name'],
            'scientific_name' => $taxon['name'],
            'category'        => $category,
            'rarity'          => $species?->rarity ?? 'common',
            'confidence'      => $confidence,
            'inaturalist_id'  => $taxon['id'],
            'photo_url'       => $taxon['default_photo']['medium_url'] ?? null,
        ]);
    }

    // DELETE /sightings/{sighting}
    public function destroy(Sighting $sighting): RedirectResponse
    {
        // $this->authorize() checks the SightingPolicy — only the owner can delete
        // TODO:
        // $this->authorize('delete', $sighting);
        // $sighting->delete();
        // return redirect()->route('dashboard');
        $this->authorize('delete', $sighting);
        $sighting->delete();
        return redirect()->route('dashboard');
    }
}
