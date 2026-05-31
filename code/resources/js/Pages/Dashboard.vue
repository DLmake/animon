<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    sightings: Array,
    stats: Object,
});

const rarityStyle = (rarity) => ({
    common:    { border: '#64748b', color: '#94a3b8' },
    uncommon:  { border: '#15803d', color: '#86efac' },
    rare:      { border: '#1d4ed8', color: '#93c5fd' },
    epic:      { border: '#7e22ce', color: '#c084fc' },
    legendary: { border: '#b45309', color: '#fcd34d' },
    extinct:   { border: '#991b1b', color: '#fca5a5' },
}[rarity] ?? { border: '#64748b', color: '#94a3b8' });

const categoryIcon = (cat) => `/icons/${cat ?? 'mammal'}.png`;
</script>

<template>
    <Head title="My Collection" />
    <AuthenticatedLayout>
        <template #header>
            <h1 style="font-family: 'Press Start 2P', monospace; font-size: 14px; color: #fff;">
                MY COLLECTION
            </h1>
        </template>

        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

            <!-- Stats bar — Pokédex screen style -->
            <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 mb-10">
                <div v-for="(stat, label) in { TOTAL: stats.total, ANIMALS: stats.animals, INSECTS: stats.insects, PLANTS: stats.plants, LEGEND: stats.legendary }"
                     :key="label"
                     class="nes-container is-dark text-center py-4">
                    <p style="font-family: 'Press Start 2P', monospace; font-size: 22px; color: #fff;">{{ stat }}</p>
                    <p style="font-family: 'Press Start 2P', monospace; font-size: 6px; color: #93c5fd; margin-top: 8px;">{{ label }}</p>
                </div>
            </div>

            <!-- Empty state -->
            <div v-if="sightings.length === 0" class="text-center py-20">
                <div class="nes-container is-dark mx-auto max-w-sm py-10">
                    <p class="text-5xl mb-6">🔍</p>
                    <p style="font-family: 'Press Start 2P', monospace; font-size: 9px; color: #93c5fd; line-height: 2; margin-bottom: 16px;">
                        NO ENTRIES FOUND
                    </p>
                    <p class="text-slate-400 text-sm mb-8">Your Pokédex is empty. Go find some wildlife!</p>
                    <Link :href="route('capture.create')" class="nes-btn is-primary" style="font-size: 8px;">
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
                        :style="`border-color: ${rarityStyle(s.species?.rarity).border} !important;`"
                    >
                        <!-- First discovery badge -->
                        <div
                            v-if="s.is_first_sighting"
                            style="position: absolute; top: 4px; right: 4px; background: #cc0000; color: #fff; font-family: 'Press Start 2P', monospace; font-size: 6px; padding: 2px 4px; z-index: 10;"
                        >
                            1ST!
                        </div>

                        <!-- Photo -->
                        <div class="w-full aspect-square overflow-hidden mb-2" style="background: #0d1b2a;">
                            <img
                                v-if="s.image?.file_url"
                                :src="s.image.file_url"
                                :alt="s.species?.common_name"
                                class="w-full h-full object-cover"
                            />
                            <div v-else class="w-full h-full flex items-center justify-center">
                                <img :src="categoryIcon(s.species?.category)" style="width: 56px; height: 56px; image-rendering: pixelated; object-fit: contain;" />
                            </div>
                        </div>

                        <!-- Name -->
                        <p style="font-family: 'Press Start 2P', monospace; font-size: 6px; line-height: 1.6; margin-bottom: 4px; color: #f1f5f9;">
                            {{ s.species?.common_name?.toUpperCase() }}
                        </p>

                        <!-- Rarity -->
                        <p :style="`font-family: 'Press Start 2P', monospace; font-size: 6px; color: ${rarityStyle(s.species?.rarity).color};`">
                            {{ s.species?.rarity?.toUpperCase() }}
                        </p>
                    </div>
                </Link>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
