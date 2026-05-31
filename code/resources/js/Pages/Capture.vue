<script setup>
import { ref } from 'vue';
import { useForm, Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const preview      = ref(null);
const identified   = ref(null);
const identifying  = ref(false);
const fileInput    = ref(null);
const locationStatus = ref('');

const form = useForm({
    photo: null,
    species_id: null,
    lat: null,
    lng: null,
    notes: '',
    ai_confidence: null,
});

function openCamera() {
    fileInput.value.click();
}

function onFileChange(e) {
    const file = e.target.files[0];
    if (!file) return;
    form.photo = file;
    preview.value = URL.createObjectURL(file);
    tryGetLocation();
}

function tryGetLocation() {
    if (!navigator.geolocation) {
        locationStatus.value = 'Location unavailable';
        return;
    }
    locationStatus.value = 'Getting location...';
    navigator.geolocation.getCurrentPosition(
        (pos) => {
            form.lat = pos.coords.latitude;
            form.lng = pos.coords.longitude;
            locationStatus.value = `${pos.coords.latitude.toFixed(4)}, ${pos.coords.longitude.toFixed(4)}`;
        },
        () => { locationStatus.value = 'Location unavailable'; }
    );
}

async function identify() {
    identifying.value = true;
    // TODO: Replace with real AI endpoint call
    // const res = await axios.post('/api/identify', { photo: form.photo });
    // identified.value = res.data;
    // For now — mock result after a delay
    await new Promise(r => setTimeout(r, 1500));
    identified.value = {
        species_id: null,
        common_name: 'Unknown Species',
        scientific_name: '',
        category: 'animal',
        rarity: 'common',
        confidence: 0.87,
    };
    form.species_id = identified.value.species_id;
    form.ai_confidence = identified.value.confidence;
    identifying.value = false;
}

function retake() {
    preview.value = null;
    identified.value = null;
    form.photo = null;
    form.species_id = null;
    form.ai_confidence = null;
    locationStatus.value = '';
}

function submit() {
    form.post(route('sightings.store'));
}
</script>

<template>
    <Head title="Capture" />
    <AuthenticatedLayout>
        <template #header>
            <h1 style="font-family: 'Press Start 2P', monospace; font-size: 14px; color: #4ade80;">
                CAPTURE
            </h1>
        </template>

        <div class="mx-auto max-w-md px-4 py-8">

            <!-- Camera zone -->
            <div class="nes-container with-title mb-4">
                <p class="title">CAMERA</p>

                <div
                    @click="openCamera"
                    class="cursor-pointer flex items-center justify-center overflow-hidden"
                    style="width: 100%; height: 260px; background: #001a00; border: 3px solid #1f2937; position: relative;"
                >
                    <img
                        v-if="preview"
                        :src="preview"
                        alt="Preview"
                        class="w-full h-full object-cover"
                        style="image-rendering: pixelated;"
                    />
                    <div v-else class="text-center">
                        <div class="text-6xl mb-4">📷</div>
                        <p style="font-family: 'Press Start 2P', monospace; font-size: 8px; color: #4ade80; line-height: 2;">
                            TAP TO CAPTURE
                        </p>
                    </div>
                </div>

                <input
                    ref="fileInput"
                    type="file"
                    accept="image/*"
                    capture="environment"
                    class="hidden"
                    @change="onFileChange"
                />
            </div>

            <!-- Location status -->
            <div v-if="locationStatus" class="nes-container is-dark mb-4 py-2">
                <p style="font-family: 'Press Start 2P', monospace; font-size: 7px; color: #00ff41;">
                    📍 {{ locationStatus }}
                </p>
            </div>

            <!-- Identify button -->
            <div v-if="preview && !identified" class="mb-4 text-center">
                <button
                    class="nes-btn is-warning w-full"
                    style="font-size: 9px; padding: 12px;"
                    :disabled="identifying"
                    @click="identify"
                >
                    {{ identifying ? '▓▓▓ IDENTIFYING... ▓▓▓' : '🔍 IDENTIFY SPECIES' }}
                </button>
            </div>

            <!-- AI result card -->
            <div v-if="identified" class="nes-container with-title mb-4">
                <p class="title">IDENTIFIED!</p>

                <div class="py-2">
                    <p style="font-family: 'Press Start 2P', monospace; font-size: 11px; color: #facc15; margin-bottom: 8px;">
                        {{ identified.common_name.toUpperCase() }}
                    </p>
                    <p v-if="identified.scientific_name" class="text-gray-400 text-xs italic mb-4">
                        {{ identified.scientific_name }}
                    </p>

                    <div class="flex gap-4 mb-4">
                        <span style="font-family: 'Press Start 2P', monospace; font-size: 7px; color: #60a5fa;">
                            {{ identified.category?.toUpperCase() }}
                        </span>
                        <span style="font-family: 'Press Start 2P', monospace; font-size: 7px; color: #4ade80;">
                            {{ identified.rarity?.toUpperCase() }}
                        </span>
                    </div>

                    <!-- Confidence bar -->
                    <p style="font-family: 'Press Start 2P', monospace; font-size: 7px; color: #9ca3af; margin-bottom: 4px;">
                        CONFIDENCE: {{ Math.round(identified.confidence * 100) }}%
                    </p>
                    <progress
                        class="nes-progress is-success"
                        :value="Math.round(identified.confidence * 100)"
                        max="100"
                    ></progress>

                    <!-- Notes -->
                    <div class="mt-4">
                        <p style="font-family: 'Press Start 2P', monospace; font-size: 7px; color: #9ca3af; margin-bottom: 8px;">
                            NOTES (OPTIONAL):
                        </p>
                        <textarea
                            v-model="form.notes"
                            class="nes-textarea w-full"
                            rows="3"
                            placeholder="Where did you spot it?"
                            style="font-size: 12px;"
                        ></textarea>
                    </div>

                    <!-- Submit -->
                    <button
                        class="nes-btn is-success w-full mt-4"
                        style="font-size: 9px; padding: 12px;"
                        :disabled="form.processing"
                        @click="submit"
                    >
                        {{ form.processing ? 'SAVING...' : '✓ ADD TO COLLECTION' }}
                    </button>

                    <!-- Errors -->
                    <p v-if="form.errors.photo" class="text-red-400 text-xs mt-2 font-pixel" style="font-size: 7px;">
                        {{ form.errors.photo }}
                    </p>
                </div>
            </div>

            <!-- Retake button -->
            <div v-if="preview" class="text-center">
                <button
                    class="nes-btn is-error"
                    style="font-size: 8px;"
                    @click="retake"
                >
                    ✕ RETAKE
                </button>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
