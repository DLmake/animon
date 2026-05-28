<script setup>
// TASK 5-F: Sighting detail page
// Shows a single catch — the photo, where/when it was caught, AI confidence, notes.

import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

// TODO: defineProps({ sighting: Object });
// sighting has: species (nested), image (nested), lat, lng, sighted_at,
//               notes, ai_confidence, is_first_global, location_name

// TODO: Delete form
// const deleteForm = useForm({});
// function deleteSighting() {
//     if (!confirm('Remove from your collection?')) return;
//     deleteForm.delete(route('sightings.destroy', props.sighting.id));
// }
</script>

<template>
    <Head title="Sighting" />
    <AuthenticatedLayout>

        <div class="mx-auto max-w-xl px-4 py-10 sm:px-6">

            <!-- TODO: Build the sighting card:
                 - Full photo (sighting.image.file_url)
                 - Species name + scientific name
                 - Date: new Date(sighting.sighted_at).toLocaleDateString()
                 - Location name (if available)
                 - AI confidence as a percentage: Math.round(sighting.ai_confidence * 100) + '%'
                 - Coordinates (if lat/lng exist)
                 - Notes (if notes exist)
                 - "🏆 You discovered this species first!" banner if is_first_global

                 Action buttons at the bottom:
                 - "View Species" → route('species.show', sighting.species.id)
                 - "Remove" → calls deleteSighting() (red/danger style)
            -->

            <Link :href="route('dashboard')" class="mt-6 inline-block text-sm text-gray-500 hover:text-gray-300">
                ← My Collection
            </Link>
        </div>

    </AuthenticatedLayout>
</template>
