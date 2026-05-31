<?php

// TASK 4: Register all routes
// Routes map a URL + HTTP method → a controller method.
// Syntax:  Route::get('/url', [ControllerClass::class, 'method'])->name('route.name');
// Names let you call route('route.name') in PHP and Vue instead of hardcoding URLs.

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SightingController;
use App\Http\Controllers\SpeciesController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// --- Public routes (no login required) ---

//how to do this create route check the controller set for it ,  check the method we made for it link them together and give them a name for vou
//we dont have a controller for welcome so we jsut render it with vou welcome

// TODO: Home page — renders Welcome.vue, pass canLogin and canRegister booleans
Route::get('/', function () {
   return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
     ]);
})->name('home');

// TODO: Map page
// Route::get('/map', ...)->name('map');
// Route::get('/map/pins', ...)->name('map.pins');  ← JSON endpoint for Leaflet markers
//we have a map controller to route us , (index funciton) and for the pins we have the 'pins method'
/*
1. User visits /map
      → Laravel returns Map.vue (empty map)
2. Map.vue loads in the browser
      → Leaflet draws the map tiles
3. Vue makes a second request to /map/pins
      → Laravel returns JSON array of coordinates
4. Vue loops through the JSON
      → Leaflet places a marker at each lat/lng

*/
Route::get('/map', [MapController::class, 'index']) ->middleware('auth') -> name('map');
Route::get('map/pins', [MapController::class, 'pins']) -> middleware('auth')->name('map.pins');


// TODO: Species (Pokédex) — public so anyone can browse even without an account
// Route::get('/species', ...)->name('species.index');
// Route::get('/species/{species}', ...)->name('species.show');
// The {species} segment is automatically fetched from DB (route model binding)
Route::get('/species', [SpeciesController::class, 'index'])->name('species.index');

//Laravel sees {species} in the route and Species $species in the controller, 
// matches them up, and runs Species::find($id) automatically. 
// If the species doesn't exist it returns a 404 automatically too
Route::get('/species/{species}',[SpeciesController::class, 'show'])->name('species.show');


// --- Auth-protected routes (login required) ---
// Wrap in middleware('auth') — unauthenticated users are redirected to /login

Route::middleware('auth')->group(function () {

    // TODO: Dashboard
    Route::get('/dashboard',[DashboardController::class,'index'] )->name('dashboard');


    // TODO: Capture flow
    Route::get('/capture', [SightingController::class,'create'])->name('capture.create');
    Route::post('/identify', [SightingController::class,'identify'])->name('identify');

    Route::post('/sightings', [SightingController::class, 'store'])->name('sightings.store'); 

    Route::get('/sightings/{sighting}', [SightingController::class,'show'])->name('sightings.show');
    Route::delete('/sightings/{sighting}', [SightingController::class,'destroy'])->name('sightings.destroy');

    // TODO: Profile (Breeze already wrote the controller for this), cluad wrotie this in
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

// This loads all the login/register/logout/password-reset routes from auth.php
require __DIR__.'/auth.php';
