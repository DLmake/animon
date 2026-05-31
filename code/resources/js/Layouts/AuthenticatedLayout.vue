<script setup>
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const showMenu = ref(false);
const page = usePage();
</script>

<template>
    <div class="min-h-screen" style="background-color: #0a0a1a;">

        <!-- Pokédex top bar -->
        <nav style="background-color: #c0392b; border-bottom: 6px solid #000; box-shadow: 0 4px 0 #7b241c;">
            <div class="mx-auto max-w-7xl px-4 py-3 flex items-center justify-between">

                <!-- Left: logo + nav links -->
                <div class="flex items-center gap-4">
                    <Link :href="route('home')" style="font-family: 'Press Start 2P', monospace; font-size: 14px; color: #fff; text-shadow: 2px 2px #000; text-decoration: none;">
                        ANIMON
                    </Link>
                    <div class="hidden sm:flex gap-2">
                        <Link :href="route('dashboard')" class="nes-btn" style="font-size: 7px; padding: 4px 8px; background: #111; color: #4ade80;">MY DEX</Link>
                        <Link :href="route('species.index')" class="nes-btn" style="font-size: 7px; padding: 4px 8px; background: #111; color: #60a5fa;">POKÉDEX</Link>
                        <Link :href="route('map')" class="nes-btn" style="font-size: 7px; padding: 4px 8px; background: #111; color: #facc15;">MAP</Link>
                    </div>
                </div>

                <!-- Right: capture + user -->
                <div class="flex items-center gap-2">
                    <Link :href="route('capture.create')" class="nes-btn is-success" style="font-size: 7px; padding: 4px 10px;">+ CATCH</Link>
                    <div class="flex gap-2">
                        <Link :href="route('profile.edit')" class="nes-btn" style="font-size: 7px; padding: 4px 8px; background: #111; color: #fff;">
                            {{ page.props.auth.user.name.toUpperCase() }}
                        </Link>
                        <Link :href="route('logout')" method="post" as="button" class="nes-btn is-error" style="font-size: 7px; padding: 4px 8px;">OUT</Link>
                    </div>
                </div>
            </div>

            <!-- Mobile menu toggle -->
            <div class="sm:hidden px-4 pb-2 flex gap-2">
                <Link :href="route('dashboard')" class="nes-btn" style="font-size: 6px; padding: 3px 6px;">MY DEX</Link>
                <Link :href="route('species.index')" class="nes-btn" style="font-size: 6px; padding: 3px 6px;">POKÉDEX</Link>
                <Link :href="route('map')" class="nes-btn" style="font-size: 6px; padding: 3px 6px;">MAP</Link>
            </div>
        </nav>

        <!-- Optional page header slot -->
        <header v-if="$slots.header" style="background-color: #0d1117; border-bottom: 3px solid #1f2937;">
            <div class="mx-auto max-w-7xl px-4 py-4">
                <slot name="header" />
            </div>
        </header>

        <!-- Page content -->
        <main>
            <slot />
        </main>
    </div>
</template>
