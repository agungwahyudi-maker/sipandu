<script setup>
import { ref,computed } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';

const page = usePage();

// Mengambil data user dari Inertia shared props
const user = computed(() => page.props.auth?.user);
const showingNavigationDropdown = ref(false);
</script>

<template>
    <div class="min-h-screen bg-slate-100/70 font-sans text-slate-800 antialiased flex flex-col md:flex-row">
        
        <!-- ================= SIDEBAR DESKTOP ================= -->
        <aside class="hidden md:flex w-72 bg-emerald-950 text-slate-100 flex-col justify-between min-h-screen sticky top-0 h-screen shrink-0 z-30 shadow-xl border-r border-emerald-900/50">
            <div>
                <!-- Brand / Logo Header -->
                <div class="h-20 flex items-center px-6 border-b border-emerald-900/60 bg-emerald-900/30">
                    <Link :href="route('dashboard')" class="flex items-center gap-3.5 group">
                        <div class="p-1 bg-emerald-600/30 border border-emerald-500/40 rounded-xl text-emerald-400 group-hover:bg-emerald-600/50 transition-all duration-300 shadow-inner">
                            <ApplicationLogo class="block h-10 w-auto fill-current text-emerald-400" />
                        </div>
                        <div class="flex flex-col">
                            <span class="font-extrabold text-base text-white tracking-wide group-hover:text-emerald-300 transition-colors">
                                Pandu HD
                            </span>
                            <span class="text-[10px] font-semibold text-emerald-400/90 tracking-wider uppercase">
                                RSUD Ngimbang
                            </span>
                        </div>
                    </Link>
                </div>

                <!-- Navigation List -->
                <nav class="p-4 space-y-1.5">
                    <div class="px-3.5 py-2 text-[10px] font-bold uppercase tracking-widest text-emerald-500/80">
                        Menu Utama
                    </div>
                    
                    <NavLink 
                        :href="route('dashboard')" 
                        :active="route().current('dashboard')"
                    >
                        <div class="p-1 rounded-lg transition-colors" :class="route().current('dashboard') ? 'text-white' : 'text-emerald-400/80 group-hover:text-emerald-300'">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                        </div>
                        <span class="tracking-wide">Dashboard</span>

                        <!-- Indicator Pill Active -->
                        <span v-if="route().current('dashboard')" class="absolute right-3 w-1.5 h-5 bg-teal-300 rounded-full shadow-sm"></span>
                    </NavLink>
                    <NavLink 
                        
                        :href="route('jurnal.index')" 
                        :active="route().current('jurnal.index')"
                    >
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Log Jurnal Pasien
                    </NavLink>
                    <!-- Section: Manajemen Sistem -->
                    <div v-if="$page.props.auth.user?.role !== 'pasien'" class="pt-4 px-3.5 py-2 text-[10px] font-bold uppercase tracking-widest text-emerald-500/80">
                        Manajemen Sistem
                    </div>

                    <!-- Menu Kelola User -->
                    <NavLink 
                        v-if="$page.props.auth.user?.role !== 'pasien'"
                        :href="route('users.index')" 
                        :active="route().current('users.*')"
                    >
                        <div class="p-1 rounded-lg transition-colors" :class="route().current('users.*') ? 'text-white' : 'text-emerald-400/80 group-hover:text-emerald-300'">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <span class="tracking-wide">Kelola User</span>
                    </NavLink>
                </nav>
            </div>

            <!-- Profile Widget Footer (No Dropdown) -->
            <div class="p-4 border-t border-emerald-900/60 bg-emerald-900/20">
                <div class="p-3 rounded-2xl bg-emerald-900/40 border border-emerald-800/60 space-y-3">
                    
                    <!-- User Info Section -->
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="relative shrink-0">
                            <div class="w-10 h-10 rounded-xl bg-emerald-600 border border-emerald-400/30 text-white font-bold text-sm flex items-center justify-center shadow-sm">
                                {{ $page.props.auth.user.name.charAt(0).toUpperCase() }}
                            </div>
                            <span class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 bg-teal-400 border-2 border-emerald-950 rounded-full"></span>
                        </div>
                        <div class="text-left min-w-0">
                            <p class="text-xs font-bold text-emerald-100 truncate">
                                {{ $page.props.auth.user.name }}
                            </p>
                            <p class="text-[10px] font-medium text-emerald-400/80 truncate">
                                {{ $page.props.auth.user.email }}
                            </p>
                        </div>
                    </div>

                    <!-- Pembatas Kelola Akun -->
                    <div class="relative flex items-center py-0.5">
                        <div class="flex-grow border-t border-emerald-800/80"></div>
                        <span class="shrink-0 px-2 text-[9px] font-bold uppercase tracking-wider text-emerald-400/70">
                            Kelola Akun
                        </span>
                        <div class="flex-grow border-t border-emerald-800/80"></div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="grid grid-cols-2 gap-1.5 pt-0.5">
                        <Link 
                            :href="route('profile.edit')" 
                            class="flex items-center justify-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-semibold text-emerald-200 hover:text-white bg-emerald-800/40 hover:bg-emerald-800/80 border border-emerald-700/50 transition-all duration-200"
                        >
                            <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span>Profile</span>
                        </Link>

                        <Link 
                            :href="route('logout')" 
                            method="post" 
                            as="button" 
                            class="w-full flex items-center justify-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-semibold text-rose-300 hover:text-rose-100 bg-rose-950/30 hover:bg-rose-900/50 border border-rose-800/40 transition-all duration-200"
                        >
                            <svg class="w-3.5 h-3.5 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span>Keluar</span>
                        </Link>
                    </div>
                </div>
            </div>
        </aside>

        <!-- ================= NAVBAR MOBILE ================= -->
        <div class="md:hidden sticky top-0 z-40 bg-emerald-950 text-white border-b border-emerald-900 shadow-md">
            <div class="flex items-center justify-between h-16 px-4">
                <Link :href="route('dashboard')" class="flex items-center gap-3">
                    <div class="p-2 bg-emerald-800 border border-emerald-700 rounded-xl text-emerald-300">
                        <ApplicationLogo class="block h-5 w-auto fill-current text-emerald-300" />
                    </div>
                    <div class="flex flex-col">
                        <span class="font-extrabold text-sm text-white">PANDU HD</span>
                        <span class="text-[9px] text-emerald-400">RSUD Ngimbang</span>
                    </div>
                </Link>

                <button
                    @click="showingNavigationDropdown = !showingNavigationDropdown"
                    class="p-2 rounded-xl text-emerald-300 hover:bg-emerald-900 focus:outline-none transition-colors"
                >
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path
                            :class="{ hidden: showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"
                        />
                        <path
                            :class="{ hidden: !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>
                </button>
            </div>

            <!-- Drawer Mobile -->
            <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }" class="sm:hidden border-t border-emerald-900 bg-emerald-950 px-4 py-5 space-y-4">
                <div class="space-y-1">
                    <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                        Dashboard
                    </ResponsiveNavLink>
                    <!-- Tambahkan Menu Log Jurnal Pasien di Sini -->
                    <ResponsiveNavLink 
                        v-if="user?.role === 'pasien'" 
                        :href="route('jurnal.index')" 
                        :active="route().current('jurnal.index')"
                    >
                        Log Jurnal Pasien
                    </ResponsiveNavLink>
                </div>

                <div class="pt-4 border-t border-emerald-900 space-y-3 mt-5">
                    <div class="px-2 flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-emerald-700 text-white font-bold text-xs flex items-center justify-center">
                            {{ $page.props.auth.user.name.charAt(0).toUpperCase() }}
                        </div>
                        <div>
                            <div class="font-bold text-sm text-white">{{ $page.props.auth.user.name }}</div>
                            <div class="text-xs text-emerald-400">{{ $page.props.auth.user.email }}</div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2 pt-2 border-t border-emerald-900">
                        <ResponsiveNavLink :href="route('profile.edit')" class="!text-center !text-xs !py-2 border border-emerald-800/80">
                            Profile
                        </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('logout')" method="post" as="button" class="!text-center !text-xs !py-2 !text-rose-400 border border-rose-900/50">
                            Keluar
                        </ResponsiveNavLink>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= MAIN CONTENT AREA ================= -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Main Content Container -->
            <main class="flex-1 p-6 sm:p-8 lg:p-10 max-w-7xl w-full mx-auto">
                <slot />
            </main>
        </div>

    </div>
</template>