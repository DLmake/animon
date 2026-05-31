<script setup>
import { onMounted, ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const mapEl  = ref(null);
const loading = ref(true);

const rarityColor = {
    common:    '#9ca3af',
    uncommon:  '#4ade80',
    rare:      '#60a5fa',
    epic:      '#c084fc',
    legendary: '#facc15',
    extinct:   '#f87171',
};

onMounted(async () => {
    const L = await import('leaflet');

    const map = L.map(mapEl.value, { center: [20, 0], zoom: 2 });

    // Dark-styled OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
    }).addTo(map);

    const res  = await fetch(route('map.pins'));
    const pins = await res.json();
    loading.value = false;

    pins.forEach(pin => {
        const color = rarityColor[pin.species?.rarity] ?? '#9ca3af';

        L.circleMarker([pin.lat, pin.lng], {
            radius:      9,
            color:       '#000',
            fillColor:   color,
            fillOpacity: 0.9,
            weight:      2,
        })
        .bindPopup(`
            <div style="font-family: 'Press Start 2P', monospace; font-size: 9px; line-height: 1.8;">
                <strong style="color: #facc15;">${pin.species?.common_name ?? 'Unknown'}</strong><br>
                <span style="color: ${color};">${(pin.species?.rarity ?? '').toUpperCase()}</span><br>
                ${pin.location_name ? `<span style="color:#9ca3af;font-size:8px;">${pin.location_name}</span>` : ''}
            </div>
        `)
        .addTo(map);
    });
});
</script>

<template>
    <Head title="Map" />
    <AuthenticatedLayout>
        <template #header>
            <h1 style="font-family: 'Press Start 2P', monospace; font-size: 14px; color: #facc15;">
                SIGHTING MAP
            </h1>
        </template>

        <div style="position: relative; height: calc(100vh - 9rem);">

            <!-- Loading overlay -->
            <div
                v-if="loading"
                style="position: absolute; inset: 0; z-index: 1000; background: #0a0a1a; display: flex; align-items: center; justify-content: center;"
            >
                <div class="nes-container is-dark text-center" style="max-width: 280px;">
                    <p style="font-family: 'Press Start 2P', monospace; font-size: 9px; color: #00ff41; line-height: 2;">
                        ▓▓▓ LOADING MAP ▓▓▓
                    </p>
                    <p class="text-gray-500 text-xs mt-2">Fetching sighting pins...</p>
                </div>
            </div>

            <!-- Map container -->
            <div ref="mapEl" style="height: 100%; width: 100%;"></div>
        </div>
    </AuthenticatedLayout>
</template>
