<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({ sighting: Object });

const deleteForm = useForm({});
function deleteSighting() {
    if (!confirm('Remove this from your collection?')) return;
    deleteForm.delete(route('sightings.destroy', props.sighting.id));
}

const rarityColor = (rarity) => ({
    common:    '#94a3b8',
    uncommon:  '#86efac',
    rare:      '#93c5fd',
    epic:      '#c084fc',
    legendary: '#fcd34d',
    extinct:   '#fca5a5',
}[rarity] ?? '#94a3b8');

const formatDate = (d) => d ? new Date(d).toLocaleDateString('en-GB', { day:'2-digit', month:'short', year:'numeric' }) : '—';
</script>

<template>
    <Head :title="sighting.species?.common_name ?? 'Sighting'" />
    <AuthenticatedLayout>

        <div class="mx-auto max-w-xl px-4 py-10">

            <!-- World first banner -->
            <div v-if="sighting.is_first_sighting" class="nes-container mb-4 text-center py-4" style="border-color:#cc0000!important; background:#1a0000!important;">
                <p style="font-family:'Press Start 2P',monospace;font-size:9px;color:#cc0000;line-height:2;">🏆 WORLD FIRST DISCOVERY! 🏆</p>
                <p class="text-slate-400 text-xs mt-2">You were the first to log this species!</p>
            </div>

            <!-- Main card -->
            <div class="nes-container with-title mb-6" :style="`border-color: ${rarityColor(sighting.species?.rarity)} !important;`">
                <p class="title">{{ sighting.species?.category?.toUpperCase() }}</p>

                <div class="w-full overflow-hidden mb-4" style="height:280px; background:#0d1b2a;">
                    <img v-if="sighting.image?.file_url" :src="sighting.image.file_url" :alt="sighting.species?.common_name" class="w-full h-full object-cover" />
                    <div v-else class="w-full h-full flex items-center justify-center text-6xl">📷</div>
                </div>

                <h1 style="font-family:'Press Start 2P',monospace;font-size:12px;color:#fff;margin-bottom:6px;line-height:1.6;">
                    {{ sighting.species?.common_name?.toUpperCase() }}
                </h1>
                <p v-if="sighting.species?.scientific_name" class="text-slate-400 italic text-sm mb-4">{{ sighting.species.scientific_name }}</p>
                <p :style="`font-family:'Press Start 2P',monospace;font-size:8px;color:${rarityColor(sighting.species?.rarity)};margin-bottom:16px;`">
                    ★ {{ sighting.species?.rarity?.toUpperCase() }}
                </p>
            </div>

            <!-- Catch data -->
            <div class="nes-container with-title mb-6" style="border-color:#1d4ed8!important;">
                <p class="title" style="color:#93c5fd!important;">CATCH DATA</p>
                <table class="w-full" style="border-collapse:collapse;">
                    <tr class="border-b border-slate-700">
                        <td style="font-family:'Press Start 2P',monospace;font-size:7px;color:#64748b;padding:8px 0;">DATE</td>
                        <td style="font-family:'Press Start 2P',monospace;font-size:7px;color:#f1f5f9;padding:8px 0;">{{ formatDate(sighting.sighted_time) }}</td>
                    </tr>
                    <tr v-if="sighting.location_name" class="border-b border-slate-700">
                        <td style="font-family:'Press Start 2P',monospace;font-size:7px;color:#64748b;padding:8px 0;">LOCATION</td>
                        <td style="font-family:'Press Start 2P',monospace;font-size:7px;color:#f1f5f9;padding:8px 0;">{{ sighting.location_name }}</td>
                    </tr>
                    <tr v-if="sighting.lat && sighting.lng" class="border-b border-slate-700">
                        <td style="font-family:'Press Start 2P',monospace;font-size:7px;color:#64748b;padding:8px 0;">GPS</td>
                        <td style="font-family:'Press Start 2P',monospace;font-size:7px;color:#93c5fd;padding:8px 0;">
                            {{ Number(sighting.lat).toFixed(4) }}, {{ Number(sighting.lng).toFixed(4) }}
                        </td>
                    </tr>
                    <tr v-if="sighting.ai_confidence" class="border-b border-slate-700">
                        <td style="font-family:'Press Start 2P',monospace;font-size:7px;color:#64748b;padding:8px 0;">AI MATCH</td>
                        <td style="padding:8px 0;">
                            <span style="font-family:'Press Start 2P',monospace;font-size:7px;color:#93c5fd;display:block;margin-bottom:4px;">
                                {{ Math.round(sighting.ai_confidence * 100) }}%
                            </span>
                            <progress class="nes-progress is-primary" :value="Math.round(sighting.ai_confidence * 100)" max="100" style="height:12px;"></progress>
                        </td>
                    </tr>
                </table>
                <div v-if="sighting.notes" class="mt-4 pt-4 border-t border-slate-700">
                    <p style="font-family:'Press Start 2P',monospace;font-size:7px;color:#64748b;margin-bottom:8px;">NOTES:</p>
                    <p class="text-slate-300 text-sm leading-relaxed">{{ sighting.notes }}</p>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex gap-3 mb-6 flex-wrap">
                <Link :href="route('species.show', sighting.species?.id)" class="nes-btn is-primary" style="font-size:8px;flex:1;text-align:center;">VIEW SPECIES</Link>
                <button class="nes-btn is-error" style="font-size:8px;flex:1;" :disabled="deleteForm.processing" @click="deleteSighting">✕ REMOVE</button>
            </div>

            <Link :href="route('dashboard')" class="nes-btn" style="font-size:8px;">← MY COLLECTION</Link>
        </div>

    </AuthenticatedLayout>
</template>
