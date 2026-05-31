<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({ canLogin: Boolean, canRegister: Boolean });
</script>

<template>
    <Head title="Animon — Catch them all" />

    <div class="min-h-screen" style="background-color: #0f1923; color: #f1f5f9;">

        <!-- Red nav bar -->
        <nav style="background-color: #cc0000; border-bottom: 6px solid #7f0000;">
            <div class="mx-auto max-w-6xl px-6 py-3 flex justify-between items-center">
                <span style="font-family: 'Press Start 2P', monospace; font-size: 16px; color: #fff; text-shadow: 2px 2px #5a0000;">
                    ANIMON
                </span>
                <div class="flex gap-3">
                    <Link v-if="canLogin"    :href="route('login')"    class="nes-btn" style="font-size: 8px; padding: 4px 10px; background:#fff!important; color:#333!important; border-color:#999!important;">LOGIN</Link>
                    <Link v-if="canRegister" :href="route('register')" class="nes-btn is-primary" style="font-size: 8px; padding: 4px 10px;">SIGN UP</Link>
                </div>
            </div>
        </nav>

        <!-- Hero — the Pokédex screen -->
        <div class="text-center py-16 px-4" style="background: linear-gradient(180deg, #0f1923 0%, #0d1b2a 100%);">
            <div class="mx-auto max-w-lg" style="border: 6px solid #334155; background: #0d1b2a; box-shadow: inset 0 0 40px #1d4ed822, 0 0 30px #1d4ed811;">
                <div py-2 style="background: #cc0000; border-bottom: 4px solid #7f0000; padding: 6px 0;">
                    <span style="font-family: 'Press Start 2P', monospace; font-size: 7px; color: #fff; letter-spacing: 4px;">
                        ● ● ●
                    </span>
                </div>
                <div class="py-12 px-6">
                    <p style="font-family: 'Press Start 2P', monospace; font-size: 8px; color: #94a3b8; margin-bottom: 8px; letter-spacing: 2px;">
                        ANIMON SYSTEM v1.0
                    </p>
                    <h1 style="font-family: 'Press Start 2P', monospace; font-size: 30px; color: #fff; text-shadow: 3px 3px #cc0000, 0 0 20px #3b82f644; margin-bottom: 16px; line-height: 1.4;">
                        ANIMON
                    </h1>
                    <p style="font-family: 'Press Start 2P', monospace; font-size: 9px; color: #93c5fd; margin-bottom: 24px; line-height: 2;">
                        CATCH REAL WILDLIFE
                    </p>
                    <p class="text-slate-400 text-sm mb-8" style="line-height: 1.7;">
                        Take a photo. AI identifies the species.<br>
                        Build your real-world Pokédex.
                    </p>
                    <div class="flex justify-center gap-4 flex-wrap">
                        <Link v-if="canRegister" :href="route('register')" class="nes-btn is-primary" style="font-size: 9px;">
                            ▶ START GAME
                        </Link>
                        <Link :href="route('species.index')" class="nes-btn" style="font-size: 9px; background:#fff!important; color:#333!important;">
                            BROWSE DEX
                        </Link>
                    </div>
                </div>
                <!-- Screen bottom bar -->
                <div style="background: #1e2d3d; border-top: 4px solid #263d52; padding: 8px 0; display: flex; justify-content: center; gap: 12px;">
                    <span style="font-family: 'Press Start 2P', monospace; font-size: 6px; color: #475569;">PRESS START</span>
                </div>
            </div>
        </div>

        <!-- Feature cards -->
        <div class="max-w-5xl mx-auto px-6 py-16 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="nes-container with-title" style="border-color: #cc0000 !important;">
                <p class="title" style="color: #cc0000 !important;">CAPTURE</p>
                <div class="text-center py-4">
                    <div class="text-5xl mb-4">📷</div>
                    <p style="font-family: 'Press Start 2P', monospace; font-size: 8px; color: #fff; margin-bottom: 12px; line-height: 2;">SNAP & IDENTIFY</p>
                    <p class="text-slate-400 text-xs leading-relaxed">Point your camera at any animal, plant or insect. AI identifies it instantly.</p>
                </div>
            </div>
            <div class="nes-container with-title" style="border-color: #1d4ed8 !important;">
                <p class="title" style="color: #93c5fd !important;">COLLECT</p>
                <div class="text-center py-4">
                    <div class="text-5xl mb-4">📖</div>
                    <p style="font-family: 'Press Start 2P', monospace; font-size: 8px; color: #fff; margin-bottom: 12px; line-height: 2;">BUILD YOUR DEX</p>
                    <p class="text-slate-400 text-xs leading-relaxed">Every species you find gets added to your personal Pokédex collection.</p>
                </div>
            </div>
            <div class="nes-container with-title" style="border-color: #475569 !important;">
                <p class="title" style="color: #94a3b8 !important;">EXPLORE</p>
                <div class="text-center py-4">
                    <div class="text-5xl mb-4">🗺️</div>
                    <p style="font-family: 'Press Start 2P', monospace; font-size: 8px; color: #fff; margin-bottom: 12px; line-height: 2;">MAP YOUR FINDS</p>
                    <p class="text-slate-400 text-xs leading-relaxed">See all your sightings pinned on an interactive world map.</p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center py-8 border-t border-slate-800">
            <p style="font-family: 'Press Start 2P', monospace; font-size: 7px; color: #475569;">
                ANIMON © 2026 — CATCH THEM ALL
            </p>
        </div>
    </div>
</template>
