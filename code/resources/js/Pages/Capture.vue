<script setup>
// TASK 5-B: Capture page
// The most interactive page. Users take a photo, get location, identify the species, then save.

import { ref } from 'vue';
import { useForm, Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

// --- Reactive state (local to this component, not from the server) ---

// TODO: Create refs for:
// const preview = ref(null);       // object URL for showing the photo preview
// const identified = ref(null);    // AI result object { common_name, confidence, ... }
// const identifying = ref(false);  // true while AI call is in progress
// const fileInput = ref(null);     // reference to the hidden <input type="file">
// const locationStatus = ref('');  // text like "Getting location..." or "40.7128, -74.0060"

// --- Inertia form (handles submission + loading state + errors) ---

// TODO: const form = useForm({
//     photo: null,
//     species_id: null,
//     lat: null,
//     lng: null,
//     notes: '',
//     ai_confidence: null,
// });

// --- Functions ---

// TODO: onFileChange(e)
// - Get file from e.target.files[0]
// - Set form.photo = file
// - Set preview.value = URL.createObjectURL(file)  ← creates a local preview URL
// - Call tryGetLocation()

// TODO: openCamera()
// - Call fileInput.value.click()  ← programmatically clicks the hidden input

// TODO: tryGetLocation()
// - Check if navigator.geolocation exists
// - Call navigator.geolocation.getCurrentPosition(successCallback, errorCallback)
// - On success: set form.lat, form.lng, update locationStatus
// - On error: set locationStatus = 'Location unavailable'

// TODO: async identify()
// - Set identifying.value = true
// - Call your backend AI endpoint (or mock with setTimeout for now)
// - On success: set identified.value = { common_name, scientific_name, category, rarity, confidence, species_id }
// - Set form.species_id and form.ai_confidence from the result
// - Set identifying.value = false

// TODO: submit()
// - form.post(route('sightings.store'))
// - Inertia sends the form as multipart/form-data (handles file uploads automatically)
</script>

<template>
    <Head title="Capture" />
    <AuthenticatedLayout>
        <template #header>
            <h1 class="text-2xl font-bold text-white">Capture</h1>
        </template>

        <div class="mx-auto max-w-xl px-4 py-10">

            <!-- TODO: Photo upload/camera zone -->
            <!-- A div that when clicked calls openCamera() -->
            <!-- When preview exists: show <img :src="preview"> filling the box -->
            <!-- When no preview: show a camera icon SVG + "Tap to take photo" text -->
            <!-- Hidden input: <input type="file" accept="image/*" capture="environment"> -->
            <!--   capture="environment" = opens rear camera on mobile -->
            <!--   ref="fileInput" + @change="onFileChange" -->


            <!-- TODO: Location status display -->
            <!-- Show when locationStatus is not empty: 📍 {{ locationStatus }} -->


            <!-- TODO: Identify button -->
            <!-- Show when preview exists AND identified is null -->
            <!-- Disabled while identifying is true -->
            <!-- Text: "Identifying..." or "Identify Species" -->


            <!-- TODO: AI result card (show when identified is not null) -->
            <!-- Show: common_name, scientific_name, category, rarity, confidence % -->
            <!-- Textarea for form.notes -->
            <!-- "Add to Collection" button → calls submit() -->


            <!-- TODO: Retake button -->
            <!-- Show when preview exists -->
            <!-- On click: reset preview, form.photo, identified to null -->

        </div>
    </AuthenticatedLayout>
</template>
