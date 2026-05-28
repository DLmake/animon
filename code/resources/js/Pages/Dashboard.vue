<script setup>
// TASK 5-A: Dashboard page
// This page receives props from DashboardController::index()
// and renders the user's collection as a grid of cards.

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

// TODO: Define the props this page receives from the controller
// defineProps({
//     sightings: Array,
//     stats: Object,
// });

// TODO: Helper — maps rarity string to Tailwind border+text color classes
// const rarityClass = (rarity) => ({
//     common:    'text-gray-400 border-gray-700',
//     uncommon:  'text-green-400 border-green-800',
//     rare:      'text-blue-400 border-blue-800',
//     legendary: 'text-yellow-400 border-yellow-700',
// }[rarity] ?? 'text-gray-400 border-gray-700');

// TODO: Helper — maps category to an emoji for fallback display
// const categoryEmoji = (cat) => ({ animal:'🦁', bird:'🐦', ... }[cat] ?? '?');
</script>

<template>
    <Head title="My Collection" />
    <AuthenticatedLayout>
        <template #header>
            <h1 class="text-2xl font-bold text-white">My Collection</h1>
        </template>

        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

            <!-- TODO: Stats bar — 5 boxes showing total, animals, insects, plants, legendaries -->
            <!-- Each box: big number + label below it -->
            <!-- Use stats.total, stats.animals, etc. -->


            <!-- TODO: Empty state — show when sightings.length === 0 -->
            <!-- Show an icon, message, and a link to /capture -->


            <!-- TODO: Collection grid — show when sightings.length > 0 -->
            <!-- v-for="s in sightings" :key="s.id" -->
            <!-- Each card:
                 - Link wrapping the whole card → route('sightings.show', s.id)
                 - Photo: s.image.file_url (or emoji fallback if no image)
                 - Species name: s.species.common_name
                 - Rarity label
                 - "1st" badge if s.is_first_global is true
                 - Border color based on rarity (use rarityClass helper)
            -->

        </div>
    </AuthenticatedLayout>
</template>
