<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    species:      Object,
    caughtByUser: Boolean,
});

const rarityColor = (rarity) => ({
    common:    '#9ca3af',
    uncommon:  '#4ade80',
    rare:      '#60a5fa',
    epic:      '#c084fc',
    legendary: '#facc15',
    extinct:   '#f87171',
}[rarity] ?? '#9ca3af');

const categoryEmoji = (cat) => ({
    mammal: '🦁', bird: '🐦', reptile: '🦎', amphibian: '🐸',
    insect: '🐛', arachnid: '🕷️', plant: '🌿', fungi: '🍄', fish: '🐟',
}[cat] ?? '❓');
</script>

<template>
    <Head :title="species.common_name" />
    <AuthenticatedLayout>

        <div class="mx-auto max-w-2xl px-4 py-10">

            <!-- Main species card -->
            <div class="nes-container with-title mb-6" :style="`border-color: ${rarityColor(species.rarity)} !important;`">
                <p class="title">{{ species.category?.toUpperCase() }}</p>

                <!-- Image -->
                <div class="w-full overflow-hidden mb-6" style="height: 240px; background: #001a00;">
                    <img
                        v-if="species.refrence_image"
                        :src="species.refrence_image"
                        :alt="species.common_name"
                        class="w-full h-full object-cover"
                        style="image-rendering: pixelated;"
                    />
                    <div v-else class="w-full h-full flex items-center justify-center text-7xl">
                        {{ categoryEmoji(species.category) }}
                    </div>
                </div>

                <!-- Name + rarity -->
                <h1 style="font-family: 'Press Start 2P', monospace; font-size: 13px; color: #f9fafb; margin-bottom: 8px; line-height: 1.6;">
                    {{ species.common_name.toUpperCase() }}
                </h1>
                <p v-if="species.scientific_name" class="text-gray-400 italic text-sm mb-4">
                    {{ species.scientific_name }}
                </p>
                <p :style="`font-family: 'Press Start 2P', monospace; font-size: 9px; color: ${rarityColor(species.rarity)}; margin-bottom: 16px;`">
                    ★ {{ species.rarity?.toUpperCase() }}
                </p>

                <!-- Description -->
                <p v-if="species.description" class="text-gray-300 text-sm leading-relaxed mb-6">
                    {{ species.description }}
                </p>
            </div>

            <!-- Taxonomy table -->
            <div class="nes-container with-title mb-6">
                <p class="title">DATA</p>
                <table class="w-full" style="border-collapse: collapse;">
                    <tr v-if="species.kingdom" class="border-b border-gray-700">
                        <td style="font-family: 'Press Start 2P', monospace; font-size: 7px; color: #9ca3af; padding: 8px 0;">KINGDOM</td>
                        <td style="font-family: 'Press Start 2P', monospace; font-size: 7px; color: #f9fafb; padding: 8px 0;">{{ species.kingdom }}</td>
                    </tr>
                    <tr v-if="species.family" class="border-b border-gray-700">
                        <td style="font-family: 'Press Start 2P', monospace; font-size: 7px; color: #9ca3af; padding: 8px 0;">FAMILY</td>
                        <td style="font-family: 'Press Start 2P', monospace; font-size: 7px; color: #f9fafb; padding: 8px 0;">{{ species.family }}</td>
                    </tr>
                    <tr v-if="species.genus" class="border-b border-gray-700">
                        <td style="font-family: 'Press Start 2P', monospace; font-size: 7px; color: #9ca3af; padding: 8px 0;">GENUS</td>
                        <td style="font-family: 'Press Start 2P', monospace; font-size: 7px; color: #f9fafb; padding: 8px 0;">{{ species.genus }}</td>
                    </tr>
                    <tr v-if="species.habitat" class="border-b border-gray-700">
                        <td style="font-family: 'Press Start 2P', monospace; font-size: 7px; color: #9ca3af; padding: 8px 0;">HABITAT</td>
                        <td style="font-family: 'Press Start 2P', monospace; font-size: 7px; color: #f9fafb; padding: 8px 0;">{{ species.habitat }}</td>
                    </tr>
                    <tr>
                        <td style="font-family: 'Press Start 2P', monospace; font-size: 7px; color: #9ca3af; padding: 8px 0;">SIGHTINGS</td>
                        <td style="font-family: 'Press Start 2P', monospace; font-size: 7px; color: #4ade80; padding: 8px 0;">{{ species.total_sightings }}</td>
                    </tr>
                </table>
            </div>

            <!-- CTA -->
            <div class="nes-container is-dark text-center py-6 mb-6">
                <div v-if="caughtByUser">
                    <p class="text-3xl mb-3">✅</p>
                    <p style="font-family: 'Press Start 2P', monospace; font-size: 9px; color: #4ade80; line-height: 2;">
                        IN YOUR COLLECTION!
                    </p>
                </div>
                <div v-else>
                    <p class="text-3xl mb-3">❓</p>
                    <p style="font-family: 'Press Start 2P', monospace; font-size: 9px; color: #9ca3af; line-height: 2; margin-bottom: 12px;">
                        NOT YET CAUGHT
                    </p>
                    <Link :href="route('capture.create')" class="nes-btn is-success" style="font-size: 8px;">
                        📷 CAPTURE THIS SPECIES
                    </Link>
                </div>
            </div>

            <!-- Back link -->
            <Link :href="route('species.index')" class="nes-btn" style="font-size: 8px;">
                ← BACK TO POKÉDEX
            </Link>
        </div>

    </AuthenticatedLayout>
</template>
