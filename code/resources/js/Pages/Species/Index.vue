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
    { value: '',          label: 'ALL',       icon: null },
    { value: 'mammal',    label: 'MAMMALS',   icon: '/icons/mammal.png' },
    { value: 'bird',      label: 'BIRDS',     icon: '/icons/bird.png' },
    { value: 'reptile',   label: 'REPTILES',  icon: '/icons/reptile.png' },
    { value: 'amphibian', label: 'AMPHIBIAN', icon: '/icons/amphibian.png' },
    { value: 'insect',    label: 'INSECTS',   icon: '/icons/insect.png' },
    { value: 'arachnid',  label: 'ARACHNID',  icon: '/icons/arachnid.png' },
    { value: 'plant',     label: 'PLANTS',    icon: '/icons/plant.png' },
    { value: 'fungi',     label: 'FUNGI',     icon: '/icons/fungi.png' },
    { value: 'fish',      label: 'FISH',      icon: '/icons/fish.png' },
];

const rarityColor = (rarity) => ({
    common:    '#94a3b8',
    uncommon:  '#86efac',
    rare:      '#93c5fd',
    epic:      '#c084fc',
    legendary: '#fcd34d',
    extinct:   '#fca5a5',
}[rarity] ?? '#94a3b8');

const caught = (id) => props.userSightingIds?.includes(id);

let debounceTimer;
watch([search, category], () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
        router.get(route('species.index'), { search: search.value, category: category.value }, { preserveState: true });
    }, 300);
});
</script>

<template>
    <Head title="Pokédex" />
    <AuthenticatedLayout>
        <template #header>
            <h1 style="font-family: 'Press Start 2P', monospace; font-size: 14px; color: #fff;">POKÉDEX</h1>
        </template>

        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

            <!-- Search -->
            <div class="nes-container with-title mb-6" style="border-color: #cc0000 !important;">
                <p class="title" style="color: #cc0000 !important;">SEARCH</p>
                <div class="flex gap-2 items-center">
                    <span style="font-size: 14px;">🔍</span>
                    <input v-model="search" type="text" class="nes-input" placeholder="Search species..." style="font-size: 12px;" />
                </div>
            </div>

            <!-- Category filters -->
            <div class="flex flex-wrap gap-2 mb-8">
                <button
                    v-for="cat in categories"
                    :key="cat.value"
                    class="nes-btn"
                    style="font-size: 7px; padding: 4px 8px;"
                    :style="category === cat.value
                        ? 'background:#cc0000!important; color:#fff!important; border-color:#7f0000!important;'
                        : 'background:#1e2d3d!important; color:#94a3b8!important; border-color:#4a6380!important;'"
                    @click="category = cat.value"
                >
                    <img v-if="cat.icon" :src="cat.icon" style="width:14px; height:14px; image-rendering:pixelated; display:inline-block; vertical-align:middle; margin-right:4px;" />
                    {{ cat.label }}
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
                        :style="caught(s.id)
                            ? `border-color: ${rarityColor(s.rarity)} !important;`
                            : 'border-color: #263d52 !important; opacity: 0.55;'"
                    >
                        <div class="w-full aspect-square overflow-hidden mb-2" style="background: #0d1b2a;">
                            <img
                                v-if="s.refrence_image && caught(s.id)"
                                :src="s.refrence_image"
                                :alt="s.common_name"
                                class="w-full h-full object-cover"
                            />
                            <div v-else class="w-full h-full flex items-center justify-center">
                                <img v-if="caught(s.id)" :src="`/icons/${s.category}.png`" style="width:56px; height:56px; image-rendering:pixelated; object-fit:contain;" />
                                <span v-else style="font-family:'Press Start 2P',monospace; font-size:18px; color:#334155;">???</span>
                            </div>
                        </div>
                        <p style="font-family:'Press Start 2P',monospace; font-size:6px; line-height:1.6; color:#f1f5f9;">
                            {{ caught(s.id) ? s.common_name.toUpperCase() : '???' }}
                        </p>
                        <p v-if="caught(s.id)" :style="`font-family:'Press Start 2P',monospace; font-size:6px; color:${rarityColor(s.rarity)}; margin-top:4px;`">
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
                    style="font-size: 8px; padding: 4px 10px; min-width: 40px;"
                    :style="link.active
                        ? 'background:#cc0000!important; color:#fff!important; border-color:#7f0000!important;'
                        : !link.url ? 'opacity:0.3; pointer-events:none;' : ''"
                    :disabled="!link.url"
                    @click="link.url && router.get(link.url)"
                    v-html="link.label"
                ></button>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
