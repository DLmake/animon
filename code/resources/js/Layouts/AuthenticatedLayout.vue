<script setup>
// TASK 5-H: Authenticated layout
// This wraps every page that requires a login (Dashboard, Capture, Map, etc.)
// It provides the nav bar and page structure that stays consistent across pages.

import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';

// TODO: const showMenu = ref(false);  // for mobile hamburger toggle (optional)
</script>

<template>
    <div class="min-h-screen bg-gray-950 text-white">

        <!-- TODO: Nav bar. Should contain:
             Left side:
               - App name "Animon" as a link to route('home')
               - Nav links: "My Collection" → route('dashboard')
                             "Pokédex"       → route('species.index')
                             "Map"           → route('map')

             Right side:
               - "+ Capture" button → route('sightings.create')
               - User dropdown (use the Dropdown + DropdownLink components):
                   Shows $page.props.auth.user.name
                   Links: Profile → route('profile.edit')
                           Log Out → route('logout') with method="post"
        -->
        <nav class="border-b border-gray-800 bg-gray-900">
            <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
                <p class="text-gray-400 text-sm">TODO: Build the nav bar (Task 5-H)</p>
            </div>
        </nav>

        <!-- Page-specific header (optional slot) -->
        <header v-if="$slots.header" class="border-b border-gray-800 bg-gray-900/50">
            <div class="mx-auto max-w-7xl px-4 py-5 sm:px-6 lg:px-8">
                <slot name="header" />
            </div>
        </header>

        <!-- Page content -->
        <main>
            <slot />
        </main>
    </div>
</template>
