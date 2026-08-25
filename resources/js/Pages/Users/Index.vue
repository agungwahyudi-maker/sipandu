<script setup>
import { ref, watch } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';

const props = defineProps({
    users: Object, // Paginated data dari controller
    filters: Object,
});

// State untuk Search & Filter
const search = ref(props.filters?.search || '');
const roleFilter = ref(props.filters?.role || '');

// Watcher untuk pencarian otomatis (Debounce)
let searchTimeout;
watch([search, roleFilter], ([newSearch, newRole]) => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        router.get(
            route('users.index'),
            { search: newSearch, role: newRole },
            { preserveState: true, replace: true }
        );
    }, 300);
});

// Modal Hapus User
const confirmingUserDeletion = ref(false);
const userIdToDelete = ref(null);
const deleteForm = useForm({});

const confirmDeleteUser = (id) => {
    userIdToDelete.value = id;
    confirmingUserDeletion.value = true;
};

const deleteUser = () => {
    deleteForm.delete(route('users.destroy', userIdToDelete.value), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;
    userIdToDelete.value = null;
};
</script>

<template>
    <Head title="Kelola User - SIKEMBANG" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="font-extrabold text-xl text-slate-800 leading-tight">
                        Kelola User & Hak Akses
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">
                        Daftar seluruh pengguna sistem SIKEMBANG RSUD Ngimbang
                    </p>
                </div>

                <!-- Tombol Tambah User -->
                <Link
                    :href="route('users.create')"
                    class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-semibold text-xs rounded-xl shadow-md shadow-emerald-900/10 transition-all duration-200"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Tambah User Baru</span>
                </Link>
            </div>
        </template>

        <div class="space-y-6">
            <!-- Filter & Search Bar Card -->
            <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
                <!-- Search Input -->
                <div class="relative w-full sm:w-80">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input
                        v-model="search"
                        type="text"
                        placeholder="Cari nama atau email..."
                        class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all"
                    />
                </div>
                
                <!-- Role Filter -->
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <select
                        v-model="roleFilter"
                        class="w-full sm:w-48 py-2.5 pl-3.5 pr-8 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all"
                    >
                        <option value="">Semua Role</option>
                        <!-- <option value="admin">Administrator</option> -->
                        <option value="pasien">Pasien</option>
                        <option value="petugas">Perawat HD</option>
                    </select>
                </div>
            </div>

            <Link
                v-if="route().has('users.create')"
                :href="route('users.create')"
                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-semibold text-xs rounded-xl shadow-md shadow-emerald-900/10 transition-all duration-200"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Tambah User Baru</span>
            </Link>
            
            <!-- Data Table Card -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                <th class="py-3.5 px-6">Pengguna</th>
                                <th class="py-3.5 px-6">Role / Jabatan</th>
                                <th class="py-3.5 px-6">Status</th>
                                <th class="py-3.5 px-6">No RM</th>
                                <th class="py-3.5 px-6">Tgl Terdaftar</th>
                                <th class="py-3.5 px-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs">
                            <tr
                                v-for="user in users.data"
                                :key="user.id"
                                class="hover:bg-slate-50/60 transition-colors"
                            >
                                <!-- Profil & Email -->
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-800 font-bold text-xs flex items-center justify-center shrink-0 border border-emerald-200">
                                            {{ user.name.charAt(0).toUpperCase() }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-bold text-slate-800 truncate">{{ user.name }}</p>
                                            <p class="text-[11px] text-slate-400 truncate">{{ user.email }}</p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Role Badge -->
                                <td class="py-4 px-6">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider"
                                        :class="{
                                            'bg-emerald-50 text-emerald-700 border border-emerald-200': user.role === 'admin',
                                            'bg-sky-50 text-sky-700 border border-sky-200': user.role === 'petugas',
                                            'bg-slate-100 text-slate-600 border border-slate-200': !['admin', 'petugas'].includes(user.role)
                                        }"
                                    >
                                        {{ user.role || 'Pegawai' }}
                                    </span>
                                </td>

                                <!-- Status Badge -->
                                <td class="py-4 px-6">
                                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-600">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                        Aktif
                                    </span>
                                </td>

                                <td class="py-4 px-6 text-slate-500 font-medium">
                                    {{ user.no_rm }}
                                </td>
                                <!-- Tanggal -->
                                <td class="py-4 px-6 text-slate-500 font-medium">
                                    {{ user.created_at }}
                                </td>

                                <!-- Tombol Aksi -->
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- Edit -->
                                        <Link
                                            :href="route('users.edit', user.id)"
                                            class="p-2 rounded-lg text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 transition-all"
                                            title="Edit User"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </Link>

                                        <!-- Hapus -->
                                        <button
                                            @click="confirmDeleteUser(user.id)"
                                            class="p-2 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-all"
                                            title="Hapus User"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Empty State -->
                            <tr v-if="users.data.length === 0">
                                <td colspan="5" class="py-12 text-center text-slate-400">
                                    <svg class="w-12 h-12 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    <p class="font-medium text-xs">Tidak ada data user yang ditemukan.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Footer -->
                <div v-if="users.links.length > 3" class="px-6 py-4 border-t border-slate-100 flex items-center justify-between">
                    <p class="text-xs text-slate-500 font-medium">
                        Menampilkan <span class="font-bold text-slate-700">{{ users.from }}</span> - <span class="font-bold text-slate-700">{{ users.to }}</span> dari <span class="font-bold text-slate-700">{{ users.total }}</span> user
                    </p>

                    <div class="flex items-center gap-1">
                        <Component
                            :is="link.url ? Link : 'span'"
                            v-for="(link, key) in users.links"
                            :key="key"
                            :href="link.url"
                            v-html="link.label"
                            class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all"
                            :class="{
                                'bg-emerald-600 text-white shadow-sm': link.active,
                                'text-slate-600 hover:bg-slate-100': link.url && !link.active,
                                'text-slate-300 cursor-not-allowed': !link.url
                            }"
                        />
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Konfirmasi Hapus -->
        <div v-if="confirmingUserDeletion" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm">
            <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl border border-slate-100 space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 text-base">Hapus Pengguna</h3>
                        <p class="text-xs text-slate-500">Apakah Anda yakin ingin menghapus user ini?</p>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2">
                    <button
                        @click="closeModal"
                        type="button"
                        class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition-all"
                    >
                        Batal
                    </button>
                    <button
                        @click="deleteUser"
                        :disabled="deleteForm.processing"
                        type="button"
                        class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold rounded-xl shadow-md transition-all"
                    >
                        Hapus Permanen
                    </button>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>