# Animon — Implementation Task List

Work through these in order. Each task tells you which file to open and what to do.
Run `php artisan serve` + `npm run dev` in two terminals so you can see changes live.

---

## PHASE 1 — Database Migrations
> Goal: define what tables and columns exist in the database.
> Run `php artisan migrate` after finishing all 5 to apply them.

- [ ] **Task 1-A** `database/migrations/..._create_species_table.php`
      Add all the columns inside the `up()` method. Follow the TODO comments.
      Check your work: `php artisan migrate` should say "DONE" with no errors.

- [ ] **Task 1-B** `database/migrations/..._create_images_table.php`
      Add columns + the foreign key to users + the perceptual_hash index.

- [ ] **Task 1-C** `database/migrations/..._create_sightings_table.php`
      Add columns + 3 foreign keys + the unique constraint (user_id, species_id).
      The unique constraint is the most important line — it enforces one Pokédex entry per user.

- [ ] **Task 1-D** `database/migrations/..._create_collections_table.php`
      Two tables in one migration: collections + the pivot table collection_sightings.

- [ ] **Task 1-E** `database/migrations/..._add_google_id_to_users_table.php`
      Modify the existing users table: add google_id, avatar_url, make password nullable.

**How to check:** `php artisan migrate` runs clean, no errors.
**To redo:** `php artisan migrate:rollback` undoes the last batch, then `migrate` again.

---

## PHASE 2 — Models
> Goal: tell Laravel how each table relates to others, and what types the columns are.
> No database changes here — models are just PHP classes.

- [ ] **Task 2-A** `app/Models/Species.php`
      Fill in $fillable, casts(), the sightings() relationship, and rarityColor().

- [ ] **Task 2-B** `app/Models/Image.php`
      Fill in $fillable, casts(), user() and sighting() relationships.

- [ ] **Task 2-C** `app/Models/Sighting.php`
      Fill in $fillable, casts(), user() + species() + image() + collections() relationships.
      Tip: collections() is a belongsToMany — needs the pivot table name as second argument.

- [ ] **Task 2-D** `app/Models/User.php`
      Add google_id to $fillable. Fill in sightings() + images() + collections() relationships.

- [ ] **Task 2-E** `app/Models/Collection.php`
      Fill in $fillable, casts(), user() + sightings() relationships.

**How to check:** Open `php artisan tinker` and try:
  `App\Models\Species::first()` → should return null (no data yet) without throwing an error.

---

## PHASE 3 — Controllers
> Goal: handle HTTP requests — load data from the DB, pass it to Vue pages.
> Each method = one URL/action in the app.

- [ ] **Task 3-A** `app/Http/Controllers/DashboardController.php`
      Uncomment the code in index(). Load sightings with relationships, calculate stats,
      return Inertia::render('Dashboard', [...]).

- [ ] **Task 3-B** `app/Http/Controllers/SightingController.php`
      Uncomment all 4 methods: create(), store(), show(), destroy().
      store() is the most complex — read through every step carefully.

- [ ] **Task 3-C** `app/Http/Controllers/SpeciesController.php`
      Uncomment index() (filtering + pagination) and show() (load species + check if caught).

- [ ] **Task 3-D** `app/Http/Controllers/MapController.php`
      Uncomment index() and pins(). Note: pins() returns response()->json(), not Inertia.

- [ ] **Task 3-E** `app/Http/Controllers/Auth/SocialAuthController.php`
      Uncomment redirect() and callback().
      You'll need GOOGLE_CLIENT_ID and GOOGLE_CLIENT_SECRET in your .env to test this.

- [ ] **Task 3-F** `app/Policies/SightingPolicy.php`
      One line: return $user->id === $sighting->user_id;

---

## PHASE 4 — Routes
> Goal: register every URL in the app and connect it to a controller method.

- [ ] **Task 4** `routes/web.php`
      Uncomment all the Route:: lines. Make sure every controller is imported at the top.

**How to check:** `php artisan route:list` should show all your routes.

---

## PHASE 5 — Vue Pages (Frontend)
> Goal: build the HTML/CSS for each page. Props come from the controller automatically.
> Each page has TODO comments describing exactly what to render.
> Tip: start with just a `<p>{{ JSON.stringify(propName) }}</p>` to verify props arrive correctly,
>      then build the real UI around them.

- [ ] **Task 5-H** `resources/js/Layouts/AuthenticatedLayout.vue` ← do this FIRST
      Everything else uses this layout. Build the nav bar.
      Until this works, all other pages will look broken.

- [ ] **Task 5-G** `resources/js/Pages/Welcome.vue`
      The landing page. Public (no auth needed). Build nav + hero + feature grid.

- [ ] **Task 5-A** `resources/js/Pages/Dashboard.vue`
      Stats bar + empty state + collection grid of cards.

- [ ] **Task 5-B** `resources/js/Pages/Capture.vue`
      Camera zone + geolocation + identify button + AI result card + submit.
      Most interactive page — build it piece by piece, test each part.

- [ ] **Task 5-C** `resources/js/Pages/Map.vue`
      Leaflet map + fetch pins + render circle markers.

- [ ] **Task 5-D** `resources/js/Pages/Species/Index.vue`
      Search + category filters + species grid + pagination.

- [ ] **Task 5-E** `resources/js/Pages/Species/Show.vue`
      Species detail card + taxonomy grid + caught/uncaught CTA.

- [ ] **Task 5-F** `resources/js/Pages/Sightings/Show.vue`
      Sighting detail + delete button.

**How to check after each page:** Visit the URL in the browser. No console errors.
`npm run dev` gives you live reload — save a file and see the change instantly.

---

## PHASE 6 — Google Login (optional, do last)
> Requires a Google Cloud Console account (free).

- [ ] Get credentials: console.cloud.google.com → New Project → APIs & Services
      → Credentials → OAuth 2.0 Client → Authorised redirect URI:
      `http://localhost:8000/auth/google/callback`

- [ ] Add to `.env`:
      ```
      GOOGLE_CLIENT_ID=your-id-here
      GOOGLE_CLIENT_SECRET=your-secret-here
      ```

- [ ] Run `php artisan config:clear`

- [ ] The Login + Register Vue pages already have the Google button.
      The SocialAuthController just needs to be uncommented (Task 3-E above).

---

## Helpful Commands

| Command | What it does |
|---|---|
| `php artisan serve` | Start the PHP dev server at localhost:8000 |
| `npm run dev` | Start Vite (live CSS/JS reloading) |
| `php artisan migrate` | Apply pending migrations |
| `php artisan migrate:rollback` | Undo the last migration batch |
| `php artisan route:list` | List all registered routes |
| `php artisan tinker` | Interactive PHP console to test queries |

## Useful Tinker commands to test your models
```php
// Count species
App\Models\Species::count();

// Find a user and load their sightings
$u = App\Models\User::first();
$u->sightings()->with('species')->get();

// Test a relationship
App\Models\Sighting::with(['species','image','user'])->first();
```
