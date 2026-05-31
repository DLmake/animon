<script setup>
import { ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    species:         Object,
    userSightingIds: Array,
    filters:         Object,
});

const search   = ref(props.filters?.search   ?? '');
const category = ref(props.filters?.category ?? '');

const categories = [
    { value: '',          label: 'ALL',       emoji: '🌍' },
    { value: 'mammal',    label: 'MAMMALS',   emoji: '🦁' },
    { value: 'bird',      label: 'BIRDS',     emoji: '🐦' },
    { value: 'reptile',   label: 'REPTILES',  emoji: '🦎' },
    { value: 'amphibian', label: 'AMPHIBIANS',emoji: '🐸' },
    { value: 'insect',    label: 'INSECTS',   emoji: '🐛' },
    { value: 'arachnid',  label: 'ARACHNIDS', emoji: '🕷️' },
    { value: 'plant',     label: 'PLANTS',    emoji: '🌿' },
    { value: 'fungi',     label: 'FUNGI',     emoji: '🍄' },
    { value: 'fish',      label: 'FISH',      emoji: '🐟' },
];

const rarityColor = (rarity) => ({
    common:    '#9ca3af',
    uncommon:  '#4ade80',
    rare:      '#60a5fa',
    epic:      '#c084fc',
    legendary: '#facc15',
    extinct:   '#f87171',
}[rarity] ?? '#9ca3af');

const caught = (id) => props.userSightingIds?.includes(id);

let debounceTimer;
watch([search, category], () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
        router.get(route('species.index'), {
            search:   search.value,
            category: category.value,
        }, { preserveState: true });
    }, 300);
});
</script>

<template>
    <Head title="Pokédex" />
    <AuthenticatedLayout>
        <template #header>
            <h1 style="font-family: 'Press Start 2P', monospace; font-size: 14px; color: #60a5fa;">
                POKÉDEX
            </h1>
        </template>

        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

            <!-- Search input -->
            <div class="nes-container with-title mb-6">
                <p class="title">SEARCH</p>
                <div class="flex gap-2 items-center">
                    <span style="font-family: 'Press Start 2P', monospace; font-size: 10px; color: #4ade80;">🔍</span>
                    <input
                        v-model="search"
                        type="text"
                        class="nes-input"
                        placeholder="Search species..."
                        style="font-size: 12px;"
                    />
                </div>
            </div>

            <!-- Category filter buttons -->
            <div class="flex flex-wrap gap-2 mb-8">
                <button
                    v-for="cat in categories"
                    :key="cat.value"
                    class="nes-btn"
                    :class="category === cat.value ? 'is-success' : ''"
                    style="font-size: 7px; padding: 4px 8px;"
                    @click="category = cat.value"
                >
                    {{ cat.emoji }} {{ cat.label }}
                </button>
            </div>

            <!-- Species grid -->
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 mb-8">
                <Link
                    v-for="s in species.data"
                    :key="s.id"
                    :href="route('species.show', s.id)"
                    class="block no-underline"
                >
                    <div
                        class="nes-container text-center cursor-pointer transition-transform hover:scale-105"
                        :style="caught(s.id) ? `border-color: ${rarityColor(s.rarity)} !important;` : 'border-color: #374151 !important; opacity: 0.6;'"
                    >
                        <!-- Image -->
                        <div class="w-full aspect-square overflow-hidden mb-2" style="background: #001a00;">
                            <img
                                v-if="s.refrence_image && caught(s.id)"
                                :src="s.refrence_image"
                                :alt="s.common_name"
                                class="w-full h-full object-cover"
                                :style="caught(s.id) ? '' : 'filter: grayscale(100%) brightness(0.3);'"
                            />
                            <div v-else class="w-full h-full flex items-center justify-center">
                                <span v-if="caught(s.id)" class="text-3xl">
                                    {{ { mammal:'🦁', bird:'🐦', reptile:'🦎', amphibian:'🐸', insect:'🐛', arachnid:'🕷️', plant:'🌿', fungi:'🍄', fish:'🐟' }[s.category] ?? '❓' }}
                                </span>
                                <span v-else style="font-family: 'Press Start 2P', monospace; font-size: 18px; color: #374151;">???</span>
                            </div>
                        </div>

                        <!-- Name -->
                        <p style="font-family: 'Press Start 2P', monospace; font-size: 6px; line-height: 1.6; color: #f9fafb;">
                            {{ caught(s.id) ? s.common_name.toUpperCase() : '???' }}
                        </p>

                        <!-- Rarity -->
                        <p v-if="caught(s.id)" :style="`font-family: 'Press Start 2P', monospace; font-size: 6px; color: ${rarityColor(s.rarity)}; margin-top: 4px;`">
                            {{ s.rarity.toUpperCase() }}
                        </p>
                    </div>
                </Link>
            </div>

            <!-- Pagination -->
            <div class="flex flex-wrap justify-center gap-2">
                <button
                    v-for="link in species.links"
                    :key="link.label"
                    class="nes-btn"
                    :class="link.active ? 'is-primary' : ''"
                    style="font-size: 8px; padding: 4px 10px; min-width: 40px;"
                    :disabled="!link.url"
                    :style="!link.url ? 'opacity: 0.3; pointer-events: none;' : ''"
                    @click="link.url && router.get(link.url)"
                    v-html="link.label"
                ></button>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
