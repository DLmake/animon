<script setup>
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const showMenu = ref(false);
const page = usePage();
</script>

<template>
    <div class="min-h-screen" style="background-color: #0f1923;">

        <!-- Pokédex red top bar -->
        <nav style="background-color: #cc0000; border-bottom: 6px solid #7f0000; box-shadow: 0 4px 0 #5a0000;">
            <div class="mx-auto max-w-7xl px-4 py-3 flex items-center justify-between">

                <!-- Left: logo + nav links -->
                <div class="flex items-center gap-4">
                    <Link :href="route('home')" style="font-family: 'Press Start 2P', monospace; font-size: 14px; color: #fff; text-shadow: 2px 2px #5a0000; text-decoration: none;">
                        ANIMON
                    </Link>
                    <div class="hidden sm:flex gap-2">
                        <Link :href="route('dashboard')"     class="nes-btn" style="font-size: 7px; padding: 4px 8px; background: #fff !important; color: #cc0000 !important; border-color: #cc0000 !important;">MY DEX</Link>
                        <Link :href="route('species.index')" class="nes-btn" style="font-size: 7px; padding: 4px 8px; background: #fff !important; color: #1d4ed8 !important; border-color: #1d4ed8 !important;">POKÉDEX</Link>
                        <Link :href="route('map')"           class="nes-btn" style="font-size: 7px; padding: 4px 8px; background: #fff !important; color: #475569 !important; border-color: #475569 !important;">MAP</Link>
                    </div>
                </div>

                <!-- Right: capture + user (logged in) -->
                <div v-if="page.props.auth.user" class="flex items-center gap-2">
                    <Link :href="route('capture.create')" class="nes-btn is-primary" style="font-size: 7px; padding: 4px 10px;">+ CATCH</Link>
                    <Link :href="route('profile.edit')" class="nes-btn" style="font-size: 7px; padding: 4px 8px; background: #fff !important; color: #333 !important; border-color: #999 !important;">
                        {{ page.props.auth.user.name.toUpperCase() }}
                    </Link>
                    <Link :href="route('logout')" method="post" as="button" class="nes-btn is-error" style="font-size: 7px; padding: 4px 8px;">OUT</Link>
                </div>

                <!-- Right: login/register (guest) -->
                <div v-else class="flex items-center gap-2">
                    <Link :href="route('login')" class="nes-btn" style="font-size: 7px; padding: 4px 8px; background: #fff !important; color: #333 !important; border-color: #999 !important;">LOGIN</Link>
                    <Link :href="route('register')" class="nes-btn is-primary" style="font-size: 7px; padding: 4px 10px;">REGISTER</Link>
                </div>
            </div>

            <!-- Mobile nav -->
            <div class="sm:hidden px-4 pb-2 flex gap-2">
                <Link :href="route('dashboard')"     class="nes-btn" style="font-size: 6px; padding: 3px 6px; background:#fff!important; color:#cc0000!important;">MY DEX</Link>
                <Link :href="route('species.index')" class="nes-btn" style="font-size: 6px; padding: 3px 6px; background:#fff!important; color:#1d4ed8!important;">POKÉDEX</Link>
                <Link :href="route('map')"           class="nes-btn" style="font-size: 6px; padding: 3px 6px; background:#fff!important; color:#475569!important;">MAP</Link>
            </div>
        </nav>

        <!-- Optional header slot -->
        <header v-if="$slots.header" style="background-color: #152131; border-bottom: 3px solid #263d52;">
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
