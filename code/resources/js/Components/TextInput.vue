<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    type: String,
    required: true,
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        class="rounded-md border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500"
        style="background-color: #0d1b2a; color: #93c5fd;"
        v-model="model"
        ref="input"
    />
</template>
