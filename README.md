# Animon

A Pokédex-style web app for logging real-world wildlife sightings.
Take a photo, let AI identify the species, and build your personal collection.

**Stack:** Laravel · Vue 3 · Inertia.js · MySQL · Tailwind CSS · NES.css · Leaflet

---

## Requirements

Make sure you have the following installed before starting:

| Tool | Version |
|------|---------|
| PHP | 8.2 or higher |
| Composer | 2.x |
| Node.js | 18 or higher |
| npm | 9 or higher |
| MySQL | 8.x (or MariaDB 10.x) |

---

## Setup

### 1. Clone the repository

```bash
git clone <your-repo-url>
cd animon
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Install JavaScript dependencies

```bash
npm install
```

### 4. Create your environment file

```bash
cp .env.example .env
php artisan key:generate
```

### 5. Configure the database

Open `.env` and update these lines with your MySQL credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=animon
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 6. Import the database

A complete SQL dump is included in the repository (`db.sql`). Import it directly — this creates all tables and loads the full species Pokédex in one step:

```bash
mysql -u root -p -e "CREATE DATABASE animon;"
mysql -u root -p animon < db.sql
```

> **Alternative** — if you prefer to run migrations and seed manually:
> ```bash
> php artisan migrate
> php artisan db:seed --class=SpeciesSeeder
> ```

### 7. Link the storage folder

So uploaded photos are publicly accessible:

```bash
php artisan storage:link
```

### 8. Build frontend assets

```bash
npm run build
```

### 9. Start the server

```bash
php artisan serve
```

Open [http://127.0.0.1:8000](http://127.0.0.1:8000) in your browser.

---

## Optional: Google OAuth login

To enable Sign in with Google, add these to your `.env`:

```env
GOOGLE_CLIENT_ID=your_client_id
GOOGLE_CLIENT_SECRET=your_client_secret
GOOGLE_REDIRECT_URI=http://127.0.0.1:8000/auth/google/callback
```

You can create credentials at [console.cloud.google.com](https://console.cloud.google.com) under **APIs & Services → Credentials**.

---

## Optional: iNaturalist AI identification

To improve species identification accuracy, add an iNaturalist API token:

```env
INATURALIST_TOKEN=your_token
```

Get a token at [inaturalist.org/users/api_token](https://www.inaturalist.org/users/api_token).
Note: tokens expire every 24 hours. The app works without a token but with lower accuracy.

After updating `.env` run:

```bash
php artisan config:clear
```

---

## Features

- **Capture** — take or upload a photo, AI identifies the species via iNaturalist
- **Pokédex** — browse all 300+ Slovenian species, filter by category
- **My Dex** — personal collection dashboard with rarity-coloured cards
- **Map** — Leaflet map showing where your sightings were recorded
- **Google OAuth** — sign in with Google in addition to email/password
