<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth.user);

// Menu Navigasi Berdasarkan Role
const menuPetugas = [
    { nama: 'Dashboard Monitoring', routeName: 'petugas.dashboard', icon: '📊' },
    { nama: 'Daftar Pasien HD', routeName: 'pasien.dashboard', icon: '👥' },
];

const menuPasien = [
    { nama: 'Jurnal Kesehatan', routeName: 'pasien.dashboard', icon: '📝' },
];

const menuList = computed(() => {
    return user.value?.role === 'petugas' ? menuPetugas : menuPasien;
});
</script>

<template>
    <aside class="w-64 bg-slate-900 text-slate-300 min-h-screen flex flex-col justify-between p-4 border-r border-slate-800">
        <!-- Header / Logo -->
        <div>
            <div class="flex items-center gap-3 px-3 py-4 mb-6 border-b border-slate-800">
                <div class="w-10 h-10 bg-sky-600 rounded-xl flex items-center justify-center text-white font-extrabold text-xl shadow-lg shadow-sky-600/30">
                    P
                </div>
                <div>
                    <h1 class="font-extrabold text-white text-base tracking-wide leading-none">PANDU <span class="text-sky-400">HD</span></h1>
                    <span class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider">RSUD Ngimbang</span>
                </div>
            </div>

            <!-- List Menu Navigasi -->
            <nav class="space-y-1">
                <div class="px-3 mb-2 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                    Menu Utama ({{ user?.role }})
                </div>

                <Link
                    v-for="(item, index) in menuList"
                    :key="index"
                    :href="route(item.routeName)"
                    :class="[
                        'flex items-center gap-3 px-3 py-2.5 rounded-xl font-bold text-xs transition-all duration-200',
                        route().current(item.routeName)
                            ? 'bg-sky-600 text-white shadow-md shadow-sky-600/20'
                            : 'hover:bg-slate-800 hover:text-white text-slate-400'
                    ]"
                >
                    <span class="text-base">{{ item.icon }}</span>
                    <span>{{ item.nama }}</span>
                </Link>
            </nav>
        </div>

        <!-- Footer / Profile & Logout -->
        <div class="pt-4 border-t border-slate-800 space-y-3">
            <!-- User Info -->
            <div class="flex items-center gap-3 px-2">
                <div class="w-9 h-9 bg-slate-800 border border-slate-700 rounded-full flex items-center justify-center font-bold text-sky-400 text-sm">
                    {{ user?.name?.charAt(0) || 'U' }}
                </div>
                <div class="overflow-hidden">
                    <p class="text-xs font-bold text-white truncate">{{ user?.name }}</p>
                    <p class="text-[10px] text-slate-400 truncate">{{ user?.email }}</p>
                </div>
            </div>

            <!-- Tombol Logout -->
            <Link
                :href="route('logout')"
                method="post"
                as="button"
                class="w-full flex items-center justify-center gap-2 py-2.5 px-3 bg-rose-500/10 hover:bg-rose-600 text-rose-400 hover:text-white font-bold text-xs rounded-xl transition-colors duration-200"
            >
                <span>🚪</span>
                <span>Keluar</span>
            </Link>
        </div>
    </aside>
</template>