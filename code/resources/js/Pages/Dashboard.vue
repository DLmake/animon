<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    sightings: Array,
    stats: Object,
});

const rarityClass = (rarity) => ({
    common:    { border: '#4b5563', color: '#9ca3af' },
    uncommon:  { border: '#16a34a', color: '#4ade80' },
    rare:      { border: '#1d4ed8', color: '#60a5fa' },
    epic:      { border: '#7e22ce', color: '#c084fc' },
    legendary: { border: '#a16207', color: '#facc15' },
    extinct:   { border: '#991b1b', color: '#f87171' },
}[rarity] ?? { border: '#4b5563', color: '#9ca3af' });

const categoryEmoji = (cat) => ({
    mammal: '🦁', bird: '🐦', reptile: '🦎',
    amphibian: '🐸', insect: '🐛', arachnid: '🕷️',
    plant: '🌿', fungi: '🍄', fish: '🐟',
}[cat] ?? '❓');
</script>

<template>
    <Head title="My Collection" />
    <AuthenticatedLayout>
        <template #header>
            <h1 style="font-family: 'Press Start 2P', monospace; font-size: 14px; color: #4ade80;">
                MY COLLECTION
            </h1>
        </template>

        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

            <!-- Stats bar -->
            <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 mb-10">
                <div class="nes-container is-dark text-center py-4">
                    <p style="font-family: 'Press Start 2P', monospace; font-size: 22px; color: #00ff41;">{{ stats.total }}</p>
                    <p style="font-family: 'Press Start 2P', monospace; font-size: 6px; color: #4ade80; margin-top: 8px;">TOTAL</p>
                </div>
                <div class="nes-container is-dark text-center py-4">
                    <p style="font-family: 'Press Start 2P', monospace; font-size: 22px; color: #00ff41;">{{ stats.animals }}</p>
                    <p style="font-family: 'Press Start 2P', monospace; font-size: 6px; color: #4ade80; margin-top: 8px;">ANIMALS</p>
                </div>
                <div class="nes-container is-dark text-center py-4">
                    <p style="font-family: 'Press Start 2P', monospace; font-size: 22px; color: #00ff41;">{{ stats.insects }}</p>
                    <p style="font-family: 'Press Start 2P', monospace; font-size: 6px; color: #4ade80; margin-top: 8px;">INSECTS</p>
                </div>
                <div class="nes-container is-dark text-center py-4">
                    <p style="font-family: 'Press Start 2P', monospace; font-size: 22px; color: #00ff41;">{{ stats.plants }}</p>
                    <p style="font-family: 'Press Start 2P', monospace; font-size: 6px; color: #4ade80; margin-top: 8px;">PLANTS</p>
                </div>
                <div class="nes-container is-dark text-center py-4">
                    <p style="font-family: 'Press Start 2P', monospace; font-size: 22px; color: #facc15;">{{ stats.legendary }}</p>
                    <p style="font-family: 'Press Start 2P', monospace; font-size: 6px; color: #facc15; margin-top: 8px;">LEGENDARY</p>
                </div>
            </div>

            <!-- Empty state -->
            <div v-if="sightings.length === 0" class="text-center py-20">
                <div class="nes-container is-dark mx-auto max-w-sm py-10">
                    <p class="text-5xl mb-6">🔍</p>
                    <p style="font-family: 'Press Start 2P', monospace; font-size: 9px; color: #00ff41; line-height: 2; margin-bottom: 16px;">
                        NO POKEMON FOUND
                    </p>
                    <p class="text-gray-400 text-sm mb-8">Your collection is empty. Go catch some wildlife!</p>
                    <Link :href="route('capture.create')" class="nes-btn is-success" style="font-size: 8px;">
                        + CATCH NOW
                    </Link>
                </div>
            </div>

            <!-- Collection grid -->
            <div v-else class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                <Link
                    v-for="s in sightings"
                    :key="s.id"
                    :href="route('sightings.show', s.id)"
                    class="block no-underline"
                >
                    <div
                        class="nes-container relative overflow-hidden cursor-pointer transition-transform hover:scale-105"
                        :style="`border-color: ${rarityClass(s.species?.rarity).border} !important;`"
                    >
                        <!-- First global badge -->
                        <div
                            v-if="s.is_first_sighting"
                            style="position: absolute; top: 4px; right: 4px; background: #facc15; color: #000; font-family: 'Press Start 2P', monospace; font-size: 6px; padding: 2px 4px; z-index: 10;"
                        >
                            1ST!
                        </div>

                        <!-- Photo -->
                        <div class="w-full aspect-square overflow-hidden mb-2" style="background: #001a00;">
                            <img
                                v-if="s.image?.file_url"
                                :src="s.image.file_url"
                                :alt="s.species?.common_name"
                                class="w-full h-full object-cover"
                                style="image-rendering: pixelated;"
                            />
                            <div v-else class="w-full h-full flex items-center justify-center text-4xl">
                                {{ categoryEmoji(s.species?.category) }}
                            </div>
                        </div>

                        <!-- Name -->
                        <p style="font-family: 'Press Start 2P', monospace; font-size: 6px; line-height: 1.6; margin-bottom: 4px; color: #f9fafb;">
                            {{ s.species?.common_name?.toUpperCase() }}
                        </p>

                        <!-- Rarity -->
                        <p :style="`font-family: 'Press Start 2P', monospace; font-size: 6px; color: ${rarityClass(s.species?.rarity).color};`">
                            {{ s.species?.rarity?.toUpperCase() }}
                        </p>
                    </div>
                </Link>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
