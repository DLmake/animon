<script setup>
// TASK 5-D: Species Index (the Pokédex browser)
// Filterable, searchable grid of all species.
// Uncaught species appear as blurred/greyed "???" — classic Pokédex effect.

import { ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

// TODO: Define props — sent by SpeciesController::index()
// defineProps({
//     species: Object,        // paginated — has .data (array), .links (pagination)
//     userSightingIds: Array, // IDs of species the user has caught
//     filters: Object,        // { search, category } currently applied
// });

// Local reactive state for the filter controls
// const search   = ref(props.filters.search ?? '');
// const category = ref(props.filters.category ?? '');

// Category buttons config
const categories = [
    { value: '', label: 'All' },
    { value: 'animal', label: 'Animals' },
    { value: 'bird', label: 'Birds' },
    { value: 'insect', label: 'Insects' },
    { value: 'plant', label: 'Plants' },
    { value: 'fungi', label: 'Fungi' },
    { value: 'reptile', label: 'Reptiles' },
    { value: 'fish', label: 'Fish' },
];

// TODO: Watch search + category, debounce 300ms, then re-fetch with updated filters
// Use router.get(route('species.index'), { search, category }, { preserveState: true })
// preserveState: true means scroll position and other state is kept during the reload
// let debounceTimer;
// watch([search, category], () => {
//     clearTimeout(debounceTimer);
//     debounceTimer = setTimeout(() => { router.get(...) }, 300);
// });

// TODO: Helper — returns Tailwind text color class for a given rarity
// const rarityClass = (rarity) => ({ ... }[rarity] ?? 'text-gray-400');

// TODO: Helper — returns true if this species ID is in the user's caught list
// const caught = (id) => props.userSightingIds.includes(id);
</script>

<template>
    <Head title="Pokédex" />
    <AuthenticatedLayout>
        <template #header>
            <h1 class="text-2xl font-bold text-white">Pokédex</h1>
        </template>

        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

            <!-- TODO: Search input (bind to search ref) -->


            <!-- TODO: Category filter buttons (loop over categories array) -->
            <!-- Active button gets a different background color -->


            <!-- TODO: Species grid -->
            <!-- v-for="s in species.data" -->
            <!-- If caught(s.id): show name, image, rarity color -->
            <!-- If not caught:  show "???", grayscale + dark overlay on image -->
            <!-- Link each card to route('species.show', s.id) -->


            <!-- TODO: Pagination links -->
            <!-- v-for="link in species.links" -->
            <!-- Use v-html="link.label" (labels contain HTML like &laquo; for arrows) -->
            <!-- Active page gets highlighted, null URL gets pointer-events-none -->

        </div>
    </AuthenticatedLayout>
</template>
