<script setup>
import { ref } from 'vue';
import { useForm, Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const preview        = ref(null);
const identified     = ref(null);
const identifying    = ref(false);
const fileInput      = ref(null);
const locationStatus = ref('');

const form = useForm({
    photo: null, species_id: null,
    lat: null, lng: null,
    notes: '', ai_confidence: null,
});

function openCamera() { fileInput.value.click(); }

function onFileChange(e) {
    const file = e.target.files[0];
    if (!file) return;
    form.photo = file;
    preview.value = URL.createObjectURL(file);
    tryGetLocation();
}

function tryGetLocation() {
    if (!navigator.geolocation) { locationStatus.value = 'Location unavailable'; return; }
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
    try {
        const data = new FormData();
        data.append('photo', form.photo);
        if (form.lat) data.append('lat', form.lat);
        if (form.lng) data.append('lng', form.lng);

        const res = await axios.post(route('identify'), data, {
            headers: { 'Content-Type': 'multipart/form-data' }
        });

        identified.value   = res.data;
        form.species_id    = res.data.species_id;
        form.ai_confidence = res.data.confidence;
    } catch (e) {
        const msg = e.response?.data?.error ?? 'Could not identify species';
        identified.value = {
            species_id: null, common_name: msg,
            scientific_name: '', category: '', rarity: 'common', confidence: 0,
        };
    }
    identifying.value = false;
}

function retake() {
    preview.value = null; identified.value = null;
    form.photo = null; form.species_id = null; form.ai_confidence = null;
    locationStatus.value = '';
}

function submit() { form.post(route('sightings.store')); }
</script>

<template>
    <Head title="Capture" />
    <AuthenticatedLayout>
        <template #header>
            <h1 style="font-family: 'Press Start 2P', monospace; font-size: 14px; color: #fff;">CAPTURE</h1>
        </template>

        <div class="mx-auto max-w-md px-4 py-8">

            <!-- Camera zone -->
            <div class="nes-container with-title mb-4" style="border-color: #cc0000 !important;">
                <p class="title" style="color: #cc0000 !important;">CAMERA</p>
                <div
                    @click="openCamera"
                    class="cursor-pointer flex items-center justify-center overflow-hidden"
                    style="width:100%; height:260px; background:#0d1b2a; border:3px solid #263d52; position:relative;"
                >
                    <img v-if="preview" :src="preview" alt="Preview" class="w-full h-full object-cover" />
                    <div v-else class="text-center">
                        <i class="nes-pokeball" style="display: block; margin: 0 auto 16px;"></i>
                        <p style="font-family: 'Press Start 2P', monospace; font-size: 8px; color: #93c5fd; line-height: 2;">TAP TO CAPTURE</p>
                    </div>
                </div>
                <input ref="fileInput" type="file" accept="image/*" capture="environment" class="hidden" @change="onFileChange" />
            </div>

            <!-- Location -->
            <div v-if="locationStatus" class="nes-container is-dark mb-4 py-2">
                <p style="font-family: 'Press Start 2P', monospace; font-size: 7px; color: #93c5fd;">
                    📍 {{ locationStatus }}
                </p>
            </div>

            <!-- Identify button -->
            <div v-if="preview && !identified" class="mb-4 text-center">
                <button
                    class="nes-btn is-primary w-full"
                    style="font-size: 9px; padding: 12px;"
                    :disabled="identifying"
                    @click="identify"
                >
                    {{ identifying ? '▓▓▓ IDENTIFYING... ▓▓▓' : '🔍 IDENTIFY SPECIES' }}
                </button>
            </div>

            <!-- AI result card -->
            <div v-if="identified" class="nes-container with-title mb-4" style="border-color: #1d4ed8 !important;">
                <p class="title" style="color: #93c5fd !important;">IDENTIFIED!</p>
                <div class="py-2">
                    <p style="font-family: 'Press Start 2P', monospace; font-size: 11px; color: #fff; margin-bottom: 8px;">
                        {{ identified.common_name?.toUpperCase() ?? 'UNKNOWN' }}
                    </p>
                    <p v-if="identified.scientific_name" class="text-slate-400 text-xs italic mb-4">{{ identified.scientific_name }}</p>
                    <div class="flex gap-4 mb-4">
                        <span style="font-family: 'Press Start 2P', monospace; font-size: 7px; color: #93c5fd;">{{ identified.category?.toUpperCase() ?? '' }}</span>
                        <span style="font-family: 'Press Start 2P', monospace; font-size: 7px; color: #94a3b8;">{{ identified.rarity?.toUpperCase() ?? '' }}</span>
                    </div>
                    <p style="font-family: 'Press Start 2P', monospace; font-size: 7px; color: #94a3b8; margin-bottom: 4px;">
                        CONFIDENCE: {{ Math.round((identified.confidence ?? 0) * 100) }}%
                    </p>
                    <progress class="nes-progress is-primary" :value="Math.round((identified.confidence ?? 0) * 100)" max="100"></progress>
                    <div class="mt-4">
                        <p style="font-family: 'Press Start 2P', monospace; font-size: 7px; color: #94a3b8; margin-bottom: 8px;">NOTES (OPTIONAL):</p>
                        <textarea v-model="form.notes" class="nes-textarea w-full" rows="3" placeholder="Where did you spot it?" style="font-size: 12px;"></textarea>
                    </div>
                    <button class="nes-btn is-primary w-full mt-4" style="font-size: 9px; padding: 12px;" :disabled="form.processing" @click="submit">
                        {{ form.processing ? 'SAVING...' : '✓ ADD TO COLLECTION' }}
                    </button>
                    <p v-if="form.errors.photo" style="font-family: 'Press Start 2P', monospace; font-size: 7px; color: #fca5a5; margin-top: 8px;">{{ form.errors.photo }}</p>
                </div>
            </div>

            <!-- Retake -->
            <div v-if="preview" class="text-center">
                <button class="nes-btn is-error" style="font-size: 8px;" @click="retake">✕ RETAKE</button>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
