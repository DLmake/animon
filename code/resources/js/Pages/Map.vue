<script setup>
// TASK 5-C: Map page
// Renders a Leaflet map and fetches sighting pins from /map/pins

import { onMounted, ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const mapEl = ref(null);  // reference to the <div> the map will mount into
const loading = ref(true);

// Rarity → marker color map
const rarityColor = {
    common:    '#9ca3af',
    uncommon:  '#4ade80',
    rare:      '#60a5fa',
    legendary: '#facc15',
};

// onMounted runs AFTER the component's HTML is in the DOM
// We MUST wait for this before initialising Leaflet (it needs a real DOM element)
onMounted(async () => {
    // TODO:
    // Step 1: Import Leaflet lazily (it's a big library, load only when needed)
    // const L = await import('leaflet');

    // Step 2: Create the map, centred on the world
    // const map = L.map(mapEl.value, { center: [20, 0], zoom: 2 });

    // Step 3: Add the OpenStreetMap tile layer (the actual map imagery)
    // L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    //     attribution: '© OpenStreetMap contributors',
    // }).addTo(map);

    // Step 4: Fetch sighting pins from our JSON endpoint
    // const res = await fetch(route('map.pins'));
    // const pins = await res.json();
    // loading.value = false;

    // Step 5: Add a circle marker for each pin
    // pins.forEach(pin => {
    //     const color = rarityColor[pin.species?.rarity] ?? '#9ca3af';
    //
    //     L.circleMarker([pin.lat, pin.lng], {
    //         radius: 8,
    //         color,
    //         fillColor: color,
    //         fillOpacity: 0.8,
    //         weight: 2,
    //     })
    //     .bindPopup(`<strong>${pin.species?.common_name}</strong><br>${pin.location_name ?? ''}`)
    //     .addTo(map);
    // });
});
</script>

<template>
    <Head title="Map" />
    <AuthenticatedLayout>
        <template #header>
            <h1 class="text-2xl font-bold text-white">Sighting Map</h1>
        </template>

        <!-- TODO: Map container -->
        <!-- The map must fill the available height. Use: h-[calc(100vh-9rem)] -->
        <!-- Add ref="mapEl" to the div — Leaflet needs this to mount into -->
        <!-- Show a loading overlay when loading is true -->

    </AuthenticatedLayout>
</template>
