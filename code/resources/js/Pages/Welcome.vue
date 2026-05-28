<script setup>
// TASK 5-G: Landing page (public, no login required)
// First thing visitors see. Should explain the app and drive signups.

import { Head, Link } from '@inertiajs/vue3';

// TODO: defineProps({ canLogin: Boolean, canRegister: Boolean });
</script>

<template>
    <Head title="Animon — Catch them all" />

    <!-- TODO: Build the landing page. Suggested sections:

         1. Nav bar
            - Left: app name/logo
            - Right: "Log in" link (if canLogin) + "Sign up" button (if canRegister)

         2. Hero section
            - Big headline, subtitle, two CTA buttons:
              "Start Collecting" → route('register')
              "Browse Pokédex"   → route('species.index')

         3. Feature grid (3 cards)
            - 📷 Snap & Identify
            - 📖 Build your Pokédex
            - 🗺️ Map your finds

         Tip: this is a GUEST page so don't use AuthenticatedLayout.
              Build the nav inline here instead.
    -->

    <div class="min-h-screen bg-gray-950 text-white">
        <p class="p-8 text-gray-400">TODO: Build the landing page (Task 5-G)</p>
    </div>
</template>
