<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({ canLogin: Boolean, canRegister: Boolean });
</script>

<template>
    <Head title="Animon — Catch them all" />

    <div class="min-h-screen" style="background-color: #0a0a1a; color: #fff;">

        <!-- Nav -->
        <nav style="background-color: #c0392b; border-bottom: 6px solid #000;">
            <div class="mx-auto max-w-6xl px-6 py-3 flex justify-between items-center">
                <span style="font-family: 'Press Start 2P', monospace; font-size: 16px; color: #fff; text-shadow: 2px 2px #000;">
                    ANIMON
                </span>
                <div class="flex gap-3">
                    <Link v-if="canLogin" :href="route('login')" class="nes-btn" style="font-size: 8px; padding: 4px 10px; background: #111; color: #fff;">
                        LOGIN
                    </Link>
                    <Link v-if="canRegister" :href="route('register')" class="nes-btn is-success" style="font-size: 8px; padding: 4px 10px;">
                        SIGN UP
                    </Link>
                </div>
            </div>
        </nav>

        <!-- Hero screen -->
        <div class="text-center py-16 px-4" style="background: linear-gradient(180deg, #0a0a1a 0%, #001a00 100%);">
            <!-- Pokédex screen border -->
            <div class="mx-auto max-w-lg p-1" style="border: 6px solid #333; background: #001a00; box-shadow: inset 0 0 30px #00ff4133, 0 0 20px #00ff4122;">
                <div class="py-12 px-6">
                    <p style="font-family: 'Press Start 2P', monospace; font-size: 10px; color: #00ff41; margin-bottom: 8px;">
                        ANIMON SYSTEM v1.0
                    </p>
                    <h1 style="font-family: 'Press Start 2P', monospace; font-size: 28px; color: #facc15; text-shadow: 3px 3px #000, 0 0 20px #facc1566; margin-bottom: 16px; line-height: 1.4;">
                        ANIMON
                    </h1>
                    <p style="font-family: 'Press Start 2P', monospace; font-size: 9px; color: #4ade80; margin-bottom: 24px; line-height: 2;">
                        CATCH REAL WILDLIFE
                    </p>
                    <p class="text-gray-400 text-sm mb-8" style="line-height: 1.7;">
                        Take a photo. AI identifies the species.<br>
                        Build your real-world Pokédex.
                    </p>
                    <div class="flex justify-center gap-4 flex-wrap">
                        <Link v-if="canRegister" :href="route('register')" class="nes-btn is-success" style="font-size: 9px;">
                            ▶ START GAME
                        </Link>
                        <Link :href="route('species.index')" class="nes-btn is-primary" style="font-size: 9px;">
                            BROWSE DEX
                        </Link>
                    </div>
                </div>
            </div>
        </div>

        <!-- Feature cards -->
        <div class="max-w-5xl mx-auto px-6 py-16 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="nes-container with-title">
                <p class="title">CAPTURE</p>
                <div class="text-center py-4">
                    <div class="text-5xl mb-4">📷</div>
                    <p style="font-family: 'Press Start 2P', monospace; font-size: 8px; color: #4ade80; margin-bottom: 12px; line-height: 2;">
                        SNAP & IDENTIFY
                    </p>
                    <p class="text-gray-400 text-xs leading-relaxed">
                        Point your camera at any animal, plant or insect. AI identifies it instantly.
                    </p>
                </div>
            </div>

            <div class="nes-container with-title">
                <p class="title">COLLECT</p>
                <div class="text-center py-4">
                    <div class="text-5xl mb-4">📖</div>
                    <p style="font-family: 'Press Start 2P', monospace; font-size: 8px; color: #60a5fa; margin-bottom: 12px; line-height: 2;">
                        BUILD YOUR DEX
                    </p>
                    <p class="text-gray-400 text-xs leading-relaxed">
                        Every species you find gets added to your personal Pokédex collection.
                    </p>
                </div>
            </div>

            <div class="nes-container with-title">
                <p class="title">EXPLORE</p>
                <div class="text-center py-4">
                    <div class="text-5xl mb-4">🗺️</div>
                    <p style="font-family: 'Press Start 2P', monospace; font-size: 8px; color: #facc15; margin-bottom: 12px; line-height: 2;">
                        MAP YOUR FINDS
                    </p>
                    <p class="text-gray-400 text-xs leading-relaxed">
                        See all your sightings pinned on an interactive world map.
                    </p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center py-8 border-t border-gray-800">
            <p style="font-family: 'Press Start 2P', monospace; font-size: 7px; color: #4b5563;">
                ANIMON © 2026 — CATCH THEM ALL
            </p>
        </div>
    </div>
</template>
