# Animon — Build It Yourself Guide

This document tells you what to build and why, in plain English.
No code is shown here on purpose — the goal is to understand what you're doing before you type it.
Work through each phase in order. Each one builds on the last.

---

## Before you start — two terminals, always open

Every time you work on this project you need two things running:

- **Terminal 1:** `php artisan serve` — this runs the PHP server so you can visit the site in a browser
- **Terminal 2:** `npm run dev` — this watches your Vue and CSS files and instantly reloads the browser when you save

Open both before you do anything else. Keep them running the whole time.
Visit `http://localhost:8000` to see the site.

---

---

# PHASE 1 — The Database (Tables & Columns)

**What this phase is:** You are designing the storage. Think of it like setting up a filing cabinet before you put anything in it. Each migration file is a set of instructions that tells the database "create a table with these columns."

**The file type:** `database/migrations/` — files that end in `_table.php`

**The command to apply your changes:** `php artisan migrate`
Run this after you finish writing a migration. If something is wrong, `php artisan migrate:rollback` undoes it.

**The golden rule:** Never edit a migration that has already been run. Instead create a new one that modifies it.

---

### Task 1-A — The Species Table

**File:** `database/migrations/2024_01_01_000010_create_species_table.php`

**What it is:** This is your Pokédex master list. Every animal, insect, and plant that can ever be discovered lives in this table. Think of it like a Wikipedia entry for each creature.

**What to add — think about what information you need to know about a species:**
- Its common name (like "American Robin") — this is required, every species needs one
- Its scientific name (like "Turdus migratorius") — optional, not everyone knows these
- What category it belongs to — is it a bird, an insect, a plant? This should only allow specific words, not free text
- Taxonomy fields — kingdom, phylum, class, order, family, genus — all optional
- A description paragraph — optional, long text
- A rarity level — common, uncommon, rare, or legendary — only these four options
- What habitat it lives in — optional text
- A URL to a reference photo — optional
- An ID from the AI service that identified it — optional, for linking back to iNaturalist
- A JSON blob to store the raw AI tags — optional, stores the AI response so you don't re-call the API
- A count of how many times it's been sighted across all users — starts at zero
- A timestamp for when it was first discovered on the platform — optional

**Tips:**
- String columns are for short text (names, URLs, codes)
- Text columns are for long text (descriptions, notes)
- Enum columns are for fixed choices (category, rarity) — define the allowed values as a list
- JSON columns store a whole array or object as one value
- Add `->nullable()` to any column that isn't required
- Add `->default(0)` to the sightings counter so new rows start at zero
- Add indexes on `category` and `scientific_name` — these are columns you'll search/filter by often

**How to know it worked:** Run `php artisan migrate`. You should see the species table listed as DONE with no errors.

---

### Task 1-B — The Images Table

**File:** `database/migrations/2024_01_01_000020_create_images_table.php`

**What it is:** Every time a user takes a photo, you save metadata about that photo here. The actual image file gets saved to disk separately — this table stores information ABOUT the file, not the file itself.

**What to add:**
- A link to the user who uploaded it — this is a foreign key, it references the users table
- The URL where the file is stored — required
- A SHA-256 hash of the file — this is a fingerprint of the exact file, used to detect exact duplicates
- A perceptual hash — a different kind of fingerprint that detects visually similar images (even if slightly different files)
- GPS coordinates from the photo's EXIF data — latitude and longitude — these are decimal numbers, optional
- Altitude from EXIF — optional decimal
- The timestamp from EXIF — when the photo was actually taken, not when it was uploaded — optional
- What device took the photo — optional text (e.g. "iPhone 15 Pro")
- Image width and height in pixels — optional small integers
- File size in bytes — optional integer

**Tips:**
- A foreign key column looks like: `foreignId('user_id')` followed by `->constrained()` followed by `->cascadeOnDelete()`
- `->constrained()` means the database will reject any user_id that doesn't exist in the users table
- `->cascadeOnDelete()` means if the user is deleted, their images are automatically deleted too
- Latitude values go from -90 to 90, so use `decimal(10, 8)` — 10 digits total, 8 after the decimal
- Longitude values go from -180 to 180, so use `decimal(11, 8)` — 11 digits total, 8 after the decimal
- Add an index on the perceptual hash column — you'll be searching it constantly to check for similar images

**How to know it worked:** Run `php artisan migrate` again. Images table appears as DONE.

---

### Task 1-C — The Sightings Table

**File:** `database/migrations/2024_01_01_000030_create_sightings_table.php`

**What it is:** This is the most important table. One row here = one user has caught one species. It's the bridge between users, species, and images. Think of it as the actual Pokédex entry — not the species info itself, but the record of "this person caught this thing here."

**What to add:**
- A foreign key to the user who made the sighting
- A foreign key to which species was identified
- A foreign key to which image was submitted
- The final GPS coordinates — these might differ slightly from the EXIF data (user could manually correct them)
- Altitude
- A human-readable location name — like "Central Park, New York" — optional text
- A two-letter country code — like "US" or "CA" — optional
- When the sighting happened — required timestamp
- Any notes the user typed — optional long text
- The AI's confidence score — a decimal number between 0 and 1 (0.94 means 94% confident)
- A true/false flag for whether this user was the FIRST person globally to log this species

**The most important constraint:** Add a unique rule across the combination of user_id and species_id together. This is what enforces "one Pokédex entry per species per user." A user can only catch each species once.

**Tips:**
- Three foreign keys, one for each of the related tables
- The unique constraint uses both columns together, not individually
- Add an index on latitude + longitude together — map queries will search by coordinates
- Add an index on user_id alone — you'll filter by user constantly

**How to know it worked:** Run `php artisan migrate`. If you try to add two rows with the same user_id + species_id, the database should reject it.

---

### Task 1-D — The Collections Tables (two tables in one migration)

**File:** `database/migrations/2024_01_01_000040_create_collections_table.php`

**What it is:** Users can group their sightings into named collections, like "Backyard Birds 2026" or "Poisonous Plants." A sighting can be in many collections. A collection can have many sightings. This kind of two-way relationship needs two tables.

**Table 1 — collections:**
- Foreign key to the user who owns it
- A name — required, short text
- A description — optional, longer text
- A public/private flag — boolean, defaults to true (public)

**Table 2 — collection_sightings (the pivot/bridge table):**
This table has no ID column of its own. It just connects the two sides.
- A foreign key to a collection
- A foreign key to a sighting
- A timestamp for when the sighting was added to this collection
- A primary key that uses BOTH foreign key columns together — this prevents the same sighting being added to the same collection twice

**Tips:**
- You can create two tables in a single migration file — just call `Schema::create()` twice
- The pivot table has no `$table->id()` and no `$table->timestamps()` — it's minimal by design
- Drop the pivot table FIRST in the `down()` method, then the main table — you can't delete a table while another table references it

**How to know it worked:** Both tables appear when you run `php artisan migrate`.

---

### Task 1-E — Add Google fields to the Users Table

**File:** `database/migrations/2024_01_01_000060_add_google_id_to_users_table.php`

**What it is:** The users table already exists — Laravel created it. This migration modifies it to add support for Google login. Instead of `Schema::create()` you use `Schema::table()` to edit an existing table.

**What to add:**
- A Google ID field — the unique ID Google assigns to every user, optional (email+password users won't have one), should be unique
- An avatar URL — the profile picture URL from Google, optional
- Make the password column optional — a user who signs in with Google never sets a password, so the column can't be required anymore

**Tips:**
- `->after('email')` places a column right after the email column for neatness
- `->nullable()->unique()` for the Google ID — optional but must be unique when present
- `->change()` at the end of a column definition modifies an existing column instead of adding a new one
- Put columns in the `down()` method too so the rollback works properly

**How to know it worked:** Run `php artisan migrate`. Check that the users table now has a google_id column.

---

---

# PHASE 2 — Models (PHP Classes for Each Table)

**What this phase is:** Migrations define the database structure. Models are the PHP code you use to actually read and write that data. Each model is a class that represents one table. When Laravel gives you a row from the database, it comes back as an instance of the model class.

**The file location:** `app/Models/`

**The key concepts:**
- `$fillable` — a list of columns that are allowed to be mass-filled. A security measure.
- `casts()` — tells Laravel to automatically convert certain columns to specific PHP types when you read them. Numbers stored as strings in the database become actual numbers. JSON stored as a string becomes an array.
- Relationships — methods that tell Laravel how one model connects to another. Laravel uses these to automatically run the JOIN queries for you.

---

### Task 2-A — Species Model

**File:** `app/Models/Species.php`

**What to define:**
- The fillable list — every column you defined in the migration (except id, created_at, updated_at — Laravel handles those automatically)
- Casts — the ai_tags column should return as a PHP array, the first_seen_at column should return as a date object
- A relationship method called `sightings` — one species has many sightings — this is a "has many" relationship because the sightings table has the foreign key (species_id)
- A helper method called `rarityColor` that returns a CSS class name based on the rarity value — legendary returns a gold/yellow class, rare returns blue, uncommon returns green, common returns grey

**Tips:**
- The $fillable array is just a list of string column names
- Cast ai_tags to 'array' so you can use it as a PHP array without manually decoding JSON
- Cast first_seen_at to 'datetime' so you get a proper date object with formatting methods
- The hasMany relationship method should return `$this->hasMany(Sighting::class)`

---

### Task 2-B — Image Model

**File:** `app/Models/Image.php`

**What to define:**
- Fillable list — all image columns
- Casts — the EXIF timestamp should be a datetime, lat and lng should be floats
- A `user()` relationship — an image belongs to a user (belongsTo, because this table has user_id)
- A `sighting()` relationship — an image has one sighting (hasOne, because the sightings table has image_id)

**Tips:**
- "Belongs to" means the foreign key is in THIS table
- "Has one/many" means the foreign key is in the OTHER table
- The difference between hasOne and hasMany is just whether you expect one result or many

---

### Task 2-C — Sighting Model

**File:** `app/Models/Sighting.php`

**What to define:**
- Fillable list — all sighting columns
- Casts — sighted_at as datetime, is_first_global as boolean, lat/lng/ai_confidence as float
- A `user()` relationship — belongs to user
- A `species()` relationship — belongs to species
- An `image()` relationship — belongs to image
- A `collections()` relationship — this one is different — it belongs to many collections through the pivot table. You need to tell Laravel the name of the pivot table and that you want to access the added_at column on that pivot table

**Tips:**
- Three of the four relationships are belongsTo — this table has all three foreign keys
- The fourth one (collections) is belongsToMany — it needs the pivot table name as the second argument
- `withPivot('added_at')` lets you access the timestamp of when something was added to a collection

---

### Task 2-D — User Model

**File:** `app/Models/User.php`

**What to define:**
- Add google_id to the fillable list (the other fields should already be there from Breeze, just add the new one)
- The `sightings()` relationship — a user has many sightings
- The `images()` relationship — a user has many images
- The `collections()` relationship — a user has many collections

**Tips:**
- This model extends Authenticatable instead of just Model — that gives it all the login/session functionality. Don't change that.
- The $hidden array (password, remember_token) is already there — don't remove it. Those fields should never be included when the model is sent as JSON to the browser.

---

### Task 2-E — Collection Model

**File:** `app/Models/Collection.php`

**What to define:**
- Fillable list — user_id, name, description, is_public
- Cast is_public as boolean
- A `user()` relationship — belongs to user
- A `sightings()` relationship — belongs to many sightings through the pivot table (mirror of what you did in the Sighting model)

---

---

# PHASE 3 — Controllers (The Logic Layer)

**What this phase is:** Controllers are the brain of the app. When a user visits a URL, a controller method runs. It reads or writes data using your models, then sends a response — either an Inertia page with data, a redirect, or JSON.

**The file location:** `app/Http/Controllers/`

**The key concept — Inertia::render():** Instead of returning HTML directly, you call `Inertia::render('PageName', ['propName' => $data])`. The 'PageName' string maps to a Vue file in `resources/js/Pages/`. The array becomes the props that the Vue component receives.

---

### Task 3-A — Dashboard Controller

**File:** `app/Http/Controllers/DashboardController.php`

**What the index() method should do:**
1. Get the currently logged-in user using Auth::user()
2. Load all of that user's sightings, including the related species and image for each one. Load them in one go (not one query per sighting) and sort them newest first
3. From those sightings, calculate five stats: total count, how many are animals, how many are insects, how many are plants, how many are legendary rarity
4. Pass both the sightings and the stats to the Dashboard Vue page

**Tips:**
- `Auth::user()` gives you the logged-in User model instance
- Calling `$user->sightings()` gives you a query builder — you can chain more methods onto it
- `->with(['species', 'image'])` loads the related models in one efficient query instead of querying the database once per row
- `->latest('sighted_at')` sorts by that column descending
- `->get()` actually executes the query and returns a collection of Sighting objects
- Once you have the collection, `->count()` counts everything, `->filter(fn($s) => ...)->count()` counts only matching items
- Check the species category via `$sighting->species->category` and rarity via `$sighting->species->rarity`

---

### Task 3-B — Sighting Controller

**File:** `app/Http/Controllers/SightingController.php`

**This is the most complex controller. Take it one method at a time.**

**create() method:** Just show the Capture Vue page. No data to load.

**store() method — the core capture flow:**
1. Validate the incoming data — photo must be an image file under 10MB, species_id must exist in the species table, lat/lng must be valid coordinates if present
2. Save the uploaded photo file to the public disk under a "sightings" folder — this stores the actual file
3. Create an Image row in the database — store the file URL, calculate and store a SHA-256 hash of the file, store the GPS coordinates, store the dimensions and file size
4. Check whether this species has ever been sighted by anyone before — if the sightings table has zero rows for this species, this is a global first discovery
5. Create or update the sighting row — if this user already has a sighting for this species, update it; if not, create a new one. Set all the location and AI fields. Set the is_first_global flag based on what you checked in step 4.
6. If it was a global first, increment the total_sightings counter on the species row
7. Redirect the user to the dashboard with a success message

**show() method:** The URL contains a sighting ID. Load the related species, image, and user data onto the sighting. Pass it to the Sightings/Show Vue page.

**destroy() method:** First check that the logged-in user is allowed to delete this sighting (use the authorize method with the policy). Then delete it. Redirect to dashboard.

**Tips:**
- Validation in Laravel is self-healing — if it fails, the user is automatically redirected back with error messages, you don't need to write that logic yourself
- `$request->file('photo')` gives you the uploaded file object
- `->store('sightings', 'public')` saves the file and returns the relative path
- `Storage::url($path)` converts a relative path to a full public URL
- `hash_file('sha256', $path)` computes the hash of a file on disk
- `updateOrCreate(['match columns'], ['set columns'])` either inserts or updates in one call
- `->doesntExist()` returns true if the query found no rows
- `->increment('column_name')` adds 1 to a numeric column in one database call

---

### Task 3-C — Species Controller

**File:** `app/Http/Controllers/SpeciesController.php`

**index() method:**
1. Start building a query — don't execute it yet
2. If the URL has a `category` parameter (like `/species?category=bird`), add a WHERE clause to filter by it
3. If the URL has a `search` parameter, add a WHERE clause that matches either the common name OR the scientific name using a LIKE search
4. Get the IDs of all species the current user has already caught — you'll need this to show caught vs uncaught in the UI
5. Execute the query with pagination (48 per page), sorted alphabetically by common name
6. Pass the paginated species, the user's caught IDs, and the current filters to the Vue page

**show() method:**
1. The species model is automatically loaded from the URL by Laravel (route model binding)
2. Load the sightings relationship, including each sighting's user
3. Check whether the current user has a sighting for this species
4. Pass the species and the caught boolean to the Vue page

**Tips:**
- Start with `Species::query()` to get a query builder you can conditionally add to
- `$request->filled('category')` returns true only if the parameter exists AND is not empty
- For the LIKE search, wrap it in a closure so the OR condition is grouped correctly: `->where(function($q) { $q->where(...)->orWhere(...) })`
- `->pluck('species_id')->toArray()` gets just the species_id column from all sightings as a plain PHP array
- `->paginate(48)` returns an object with a `data` array (the results) and a `links` array (pagination nav) — Inertia handles the serialization automatically

---

### Task 3-D — Map Controller

**File:** `app/Http/Controllers/MapController.php`

**index() method:** Just return the Map Vue page. The map page loads its own data after it mounts, so no data to pass here.

**pins() method:**
1. Build a query for sightings that have both a lat and a lng value
2. Load the related species (just the fields the map needs — id, common_name, category, rarity, reference_image)
3. Only select the columns the map needs from the sightings table — id, species_id, lat, lng, location_name, sighted_at
4. If the user is logged in, only show their own pins
5. Return the results as a JSON response — NOT an Inertia response

**Tips:**
- `->whereNotNull('lat')->whereNotNull('lng')` filters out sightings with no coordinates
- `with('species:id,common_name,category,rarity')` loads only specific columns of the relationship — faster than loading everything
- `response()->json($data)` returns a JSON response that the Vue fetch() call will receive
- This is the only controller method in the whole app that returns JSON instead of an Inertia page

---

### Task 3-E — Social Auth Controller (Google Login)

**File:** `app/Http/Controllers/Auth/SocialAuthController.php`

**redirect() method:** One line. Tell the Socialite library to redirect the user to Google's login page. Socialite reads your Google credentials from the config and builds the correct URL automatically.

**callback() method:**
1. Wrap everything in a try/catch — if the user denied access or something went wrong, redirect to login with an error message
2. Ask Socialite to exchange the code Google sent back for the user's profile information
3. Look up whether a user with this Google ID already exists in your database
4. If not found by Google ID, try to find them by email instead (handles the case where they registered with email first)
5. If found — update their google_id and avatar_url in case they changed either
6. If not found — create a brand new user. Generate a username from their name (make it URL-safe, handle duplicates by adding a number suffix). Set password to null.
7. Log the user in with a remember-me cookie
8. Redirect them to wherever they were trying to go, defaulting to the dashboard

**Tips:**
- `Socialite::driver('google')->user()` does the whole token exchange and profile fetch in one call
- It returns an object with methods: `->getId()`, `->getName()`, `->getEmail()`, `->getAvatar()`
- `??` is the null-coalescing operator — `$a ?? $b` means "use $a if it's not null, otherwise use $b"
- `Str::slug('Hello World')` returns 'hello-world' — good for generating usernames
- `redirect()->intended(route('dashboard'))` redirects to what they originally wanted, falling back to dashboard

---

### Task 3-F — Sighting Policy

**File:** `app/Policies/SightingPolicy.php`

**What it does:** Answers one question — "can this user delete this sighting?" The answer is yes if and only if the user is the one who created it.

**The delete() method:** Compare the logged-in user's ID to the sighting's user_id. Return true if they match, false if not. That's literally the entire implementation — one comparison, one return.

**Tip:** Laravel automatically finds this policy because the class name matches the model name (SightingPolicy → Sighting model). No registration needed.

---

---

# PHASE 4 — Routes (The URL Map)

**What this phase is:** Routes tell Laravel "when someone visits this URL, run this controller method." This is the one file that ties all your controllers together.

**The file:** `routes/web.php`

**What to register:**

**Public routes (no login required):**
- The home page at `/` — renders the Welcome Vue page, passes two booleans telling it whether login and register routes exist
- The map page at `/map`
- The map pins JSON endpoint at `/map/pins`
- The species list at `/species`
- The species detail page at `/species/{species}` — the curly braces mean Laravel automatically fetches the Species model with that ID from the database and passes it to the controller

**Protected routes (login required — wrap in middleware group):**
- The dashboard at `/dashboard`
- The capture page at `/capture`
- The sighting save endpoint at `/sightings` — this is a POST route, not a GET
- The sighting detail page at `/sightings/{sighting}`
- The sighting delete endpoint at `/sightings/{sighting}` — this is a DELETE route, not a GET
- Profile edit, update, and delete — the controllers for these already exist (Breeze wrote them)

**Tips:**
- `Route::get()` for pages the user visits
- `Route::post()` for forms being submitted
- `Route::delete()` for delete actions
- `Route::middleware('auth')->group(function() { ... })` wraps multiple routes so they all require login
- `->name('some.name')` gives a route a name so you can reference it as `route('some.name')` everywhere instead of hardcoding URLs
- The `auth.php` file is already included at the bottom — that handles login, register, logout, password reset

**How to check:** Run `php artisan route:list` in the terminal. You should see all your routes listed.

---

---

# PHASE 5 — Vue Pages (The User Interface)

**What this phase is:** The visual layer. Each file in `resources/js/Pages/` is a page of the app. Props sent from the controller arrive as reactive variables you can use in the template.

**The key concepts:**
- `defineProps()` — declares which props this page receives from the controller
- `ref()` — creates a reactive variable that's local to this component (not from the server)
- `v-for` — loops over an array and renders something for each item
- `v-if` — conditionally shows or hides something
- `:href` — a dynamic attribute bound to a variable (with the colon) vs `href` which is plain text
- `{{ }}` — outputs a value as text in the template
- Tailwind classes go directly on HTML elements — no separate CSS file needed

**Start with Task 5-H — the layout — before anything else. Every other page uses it.**

---

### Task 5-H — Authenticated Layout (DO THIS FIRST)

**File:** `resources/js/Layouts/AuthenticatedLayout.vue`

**What it is:** The shell that wraps every logged-in page. Contains the nav bar, header slot, and main content slot. If this is broken, all other pages will look broken too.

**The nav bar should have:**
- Left side: the app name as a clickable link to the home route
- Left side: three navigation links — My Collection, Pokédex, Map — linking to their respective routes
- Right side: a green "+ Capture" button linking to the capture route
- Right side: a dropdown showing the user's name — clicking it reveals Profile and Log Out links
- The current user's name comes from `$page.props.auth.user.name` — Inertia provides this on every page automatically

**The rest of the layout:**
- An optional header slot — shows between the nav and main content when a page provides a header
- A main content slot — where each page's actual content goes

**Tips:**
- Use the Dropdown and DropdownLink components that Breeze already provides — import and use them, don't build your own
- The Log Out link must use `method="post"` as an attribute — logging out is a POST request for security reasons, not a GET
- `route('dashboard')` works in Vue because Ziggy injects all your PHP routes into the page

---

### Task 5-G — Welcome Page

**File:** `resources/js/Pages/Welcome.vue`

**What it receives:** Two booleans — canLogin and canRegister — telling it whether to show those links

**What to build:**
- A nav bar at the top with the app name on the left and login/signup links on the right — only show each link if the corresponding prop is true
- A big hero section in the middle of the screen — large headline, a subtitle describing the app, two call-to-action buttons (Start Collecting → register route, Browse Pokédex → species index route)
- Three feature cards at the bottom explaining the app — camera/identify, collection, map

**Tip:** This page doesn't use AuthenticatedLayout since the user isn't logged in. Build the nav inline in this file itself.

---

### Task 5-A — Dashboard Page

**File:** `resources/js/Pages/Dashboard.vue`

**What it receives:** An array of sightings (each with a nested species and image), and a stats object with counts

**What to build:**
- A row of five stat boxes at the top — Total, Animals, Insects, Plants, Legendaries — each showing a big number and a label
- If the sightings array is empty: an empty state with a message and a link to the capture page
- If there are sightings: a grid of cards, six columns wide on large screens, two on small screens
- Each card shows the photo, the species name, the rarity label, and a colored border matching the rarity
- If is_first_global is true on a sighting, show a small "1st" badge on the corner of the card
- Each card links to that sighting's detail page

**Tips:**
- Use a JS object to map rarity strings to CSS class strings — `{ common: 'border-gray-700', rare: 'border-blue-800', ... }`
- `v-if` and `v-else` let you show different things based on a condition
- The photo URL is at `sighting.image.file_url` — it might be null, so check with `v-if` before using it
- `aspect-square` is a Tailwind class that makes a box perfectly square

---

### Task 5-B — Capture Page

**File:** `resources/js/Pages/Capture.vue`

**What it receives:** Nothing from the server — all state is local to this component

**What to build — build each piece one at a time:**

**Piece 1 — The photo zone:**
A clickable box that when clicked triggers a hidden file input. The file input has `capture="environment"` which tells mobile browsers to open the camera directly. When a file is selected, generate a preview URL from the file and display it filling the box. Show a camera icon when no photo is selected yet.

**Piece 2 — The location grabber:**
As soon as a photo is selected, try to get the user's GPS coordinates from the browser's location API. Show "Getting location..." while waiting, then show the coordinates (or an error message). Store the coordinates so they get sent with the form.

**Piece 3 — The identify button:**
Only show this when a photo is selected but not yet identified. When clicked, set a loading state and call your AI identification endpoint (or use a fake response for now). When done, store the result.

**Piece 4 — The result card:**
Only show this after identification. Display the species name, scientific name, category, rarity, and confidence percentage. Include a text area for notes. Include an "Add to Collection" button that submits the form.

**Piece 5 — The retake button:**
Show when a photo is selected. Clicking it clears the photo, preview, and identification result so the user can start over.

**Tips:**
- `useForm()` from Inertia creates a reactive form object with a `.post()` method that handles file uploads automatically
- `URL.createObjectURL(file)` creates a temporary URL you can use as an image src to show a preview
- `navigator.geolocation.getCurrentPosition()` takes a success callback and an error callback
- The form is submitted with `form.post(route('sightings.store'))` — Inertia handles the multipart file upload
- `form.processing` is automatically true while the form is submitting — use it to disable the button

---

### Task 5-C — Map Page

**File:** `resources/js/Pages/Map.vue`

**What it receives:** Nothing from the server

**What to build:**
- A full-height div that will become the map — give it a `ref` attribute so you can reference it in the script
- A loading indicator that shows until the map data is fetched
- In the script, use `onMounted` to initialize Leaflet after the component appears in the DOM

**The map initialization — do these steps in order:**
1. Import Leaflet (do this lazily inside onMounted, not at the top of the file — Leaflet is large)
2. Create the map in the referenced div, centered on coordinates [20, 0] (center of the world) at zoom level 2
3. Add the OpenStreetMap tile layer — this provides the actual map imagery from openstreetmap.org
4. Fetch the pins data from the `/map/pins` endpoint using the browser's fetch API
5. For each pin in the response, add a colored circle marker at its coordinates — use the species rarity to determine the color
6. Make each marker show a popup with the species name and location when clicked

**Tips:**
- Leaflet MUST be initialized after the DOM element exists — that's why `onMounted` is essential
- `import('leaflet')` inside a function is a dynamic/lazy import — it downloads Leaflet only when this page is visited
- `L.map(element, options)` creates the map — the element is `mapEl.value` (the ref's current DOM node)
- `L.tileLayer(url, options).addTo(map)` adds the background map tiles
- `L.circleMarker([lat, lng], styleOptions).bindPopup(htmlString).addTo(map)` creates a clickable marker
- `route('map.pins')` gives you the URL — use it with `fetch()`

---

### Task 5-D — Species Index (Pokédex browser)

**File:** `resources/js/Pages/Species/Index.vue`

**What it receives:** A paginated species object (with `.data` array and `.links` array), the user's caught species IDs, and the current filter values

**What to build:**
- A search text input — when the user types, wait 300ms then reload the page with the search term as a URL parameter (use Inertia's router.get for this, not a regular form submit)
- Category filter buttons — clicking one reloads the page filtered to that category
- A grid of species cards — for each species, show it differently based on whether the user has caught it
  - Caught species: full color photo, name, rarity label
  - Uncaught species: greyscale or dark photo, "???" instead of name, mystery overlay
- Pagination links at the bottom

**Tips:**
- `watch()` lets you run a function whenever a reactive value changes
- `setTimeout` / `clearTimeout` creates the 300ms debounce so you don't reload on every single keystroke
- `router.get(url, params, { preserveState: true })` reloads the current page with new query parameters without losing scroll position
- Check if a species is caught with `userSightingIds.includes(species.id)`
- Tailwind's `grayscale` class makes an image black and white

---

### Task 5-E — Species Detail Page

**File:** `resources/js/Pages/Species/Show.vue`

**What it receives:** The species object with all its fields, and a boolean for whether the current user has caught it

**What to build:**
- A large card with the reference photo at the top
- The common name as the main heading, scientific name as italic subtitle below
- A colored rarity badge
- The description paragraph if it exists
- A small grid of info boxes — kingdom, family, habitat, total global sightings
- At the bottom, either:
  - A green "In your collection" box if the user has already caught this species
  - A "Capture this species" button linking to the capture page if they haven't
- A back link to the Pokédex list

---

### Task 5-F — Sighting Detail Page

**File:** `resources/js/Pages/Sightings/Show.vue`

**What it receives:** The sighting object with the nested species, image, and user data

**What to build:**
- The photo taking up most of the top of the card
- The species name and scientific name
- A grid of detail boxes: date sighted, location name, AI confidence as a percentage, coordinates if available
- The user's notes if they wrote any
- If is_first_global is true: a special gold banner saying they were the first to discover this species
- Two buttons at the bottom: "View Species" (links to the species detail page) and "Remove" (a danger button that deletes the sighting after a confirmation dialog)

**Tips:**
- `new Date(sighting.sighted_at).toLocaleDateString()` formats the date nicely
- `Math.round(sighting.ai_confidence * 100)` converts 0.94 to 94
- The delete button should use `useForm({})` from Inertia and call `form.delete(route(...))` — this sends a DELETE HTTP request
- Wrap the delete call in `confirm('Are you sure?')` to show a browser confirmation dialog first

---

---

# PHASE 6 — Google Login (Do This Last)

**What this phase is:** Connecting to Google's OAuth service so users can sign in with their Google account instead of creating a new password.

**Step 1 — Get your credentials from Google:**
Go to console.cloud.google.com and create a new project. Go to APIs and Services, then Credentials. Create an OAuth 2.0 Client ID for a Web Application. Add `http://localhost:8000/auth/google/callback` as an Authorised Redirect URI. Copy the Client ID and Client Secret.

**Step 2 — Add them to your .env file:**
Find the two lines near the bottom that say `GOOGLE_CLIENT_ID=` and `GOOGLE_CLIENT_SECRET=` and paste your values in. Don't put quotes around them.

**Step 3 — Clear the config cache:**
Run `php artisan config:clear` — Laravel caches config files for performance, so you need to clear the cache after changing .env.

**Step 4 — Implement the controller:**
The SocialAuthController already has instructions in it from Task 3-E. Implement both methods now that you have real credentials to test with.

**How to test:** Visit `/login`, click "Continue with Google," pick a Google account. You should end up on the dashboard. Check the users table in the database — a new row should have appeared with your Google ID and no password.

---

---

# Testing Each Phase

After each phase, here's how to check your work without looking at the finished code:

| After Phase | How to test |
|---|---|
| Migrations | `php artisan migrate` runs with no errors |
| Models | `php artisan tinker` → type a model query, no PHP errors |
| Controllers | Visit a route in the browser, no 500 error |
| Routes | `php artisan route:list` shows all expected URLs |
| Vue pages | Visit each page in the browser, correct content appears |

**When something breaks:**
- A red 500 error page → PHP error → check your terminal running `php artisan serve` for the exact error
- A white/blank page → Vue error → open browser DevTools (F12) → Console tab for the error message
- "Route not found" → the route isn't registered in web.php yet
- "Class not found" → the controller/model isn't imported at the top of the file

---

# You've got this.
Work through one task at a time. Run the app after every small change.
When something breaks, read the error message carefully — it almost always tells you exactly what's wrong and which line to look at.
