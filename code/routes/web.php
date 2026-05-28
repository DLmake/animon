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

// TODO: Home page — renders Welcome.vue, pass canLogin and canRegister booleans
// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin'    => Route::has('login'),
//         'canRegister' => Route::has('register'),
//     ]);
// })->name('home');

// TODO: Map page
// Route::get('/map', ...)->name('map');
// Route::get('/map/pins', ...)->name('map.pins');  ← JSON endpoint for Leaflet markers

// TODO: Species (Pokédex) — public so anyone can browse even without an account
// Route::get('/species', ...)->name('species.index');
// Route::get('/species/{species}', ...)->name('species.show');
// The {species} segment is automatically fetched from DB (route model binding)


// --- Auth-protected routes (login required) ---
// Wrap in middleware('auth') — unauthenticated users are redirected to /login

// Route::middleware('auth')->group(function () {

    // TODO: Dashboard
    // Route::get('/dashboard', ...)->name('dashboard');

    // TODO: Capture flow
    // Route::get('/capture', ...)->name('sightings.create');   ← show the camera page
    // Route::post('/sightings', ...)->name('sightings.store'); ← receive the photo
    // Route::get('/sightings/{sighting}', ...)->name('sightings.show');
    // Route::delete('/sightings/{sighting}', ...)->name('sightings.destroy');

    // TODO: Profile (Breeze already wrote the controller for this)
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

// });

// This loads all the login/register/logout/password-reset routes from auth.php
require __DIR__.'/auth.php';
