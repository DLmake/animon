<script setup>
// TASK 5-E: Species detail page
// Shows everything about one species + whether the current user has caught it.

import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

// TODO: defineProps
// const props = defineProps({
//     species: Object,      // the species model with all fields
//     caughtByUser: Boolean, // true if this user has a sighting for this species
// });

// Optional: rarity → Tailwind classes lookup object
// const rarityClass = { common: '...', uncommon: '...', rare: '...', legendary: '...' };
</script>

<template>
    <!-- TODO: Head title should be the species common name: <Head :title="species.common_name" /> -->
    <Head title="Species" />
    <AuthenticatedLayout>

        <div class="mx-auto max-w-2xl px-4 py-10 sm:px-6">

            <!-- TODO: Build the species card with:
                 - Reference image (or emoji fallback)
                 - common_name (large heading)
                 - scientific_name (italic subtitle)
                 - Rarity badge (colored)
                 - description paragraph
                 - Taxonomy grid: kingdom, family, habitat, total_sightings
                 - CTA at the bottom:
                     If caughtByUser: show "✓ In your collection" (green box)
                     If not:          show "Capture this species" link → /capture
            -->

            <!-- Back link -->
            <Link :href="route('species.index')" class="mt-6 inline-block text-sm text-gray-500 hover:text-gray-300">
                ← Back to Pokédex
            </Link>
        </div>

    </AuthenticatedLayout>
</template>
