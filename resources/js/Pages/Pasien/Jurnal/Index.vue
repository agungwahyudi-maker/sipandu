<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue'; // opsional jika ada pagination
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    logs: Object
});

const selectedLogMakanan = ref(null);

const formatDateTime = (dateString) => {
    if (!dateString) return '-';
    return new Date(dateString).toLocaleString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};
</script>

<template>
    <Head title="Log Jurnal Pasien - SIKEMBANG" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-bold text-xl text-slate-800 leading-tight">
                Riwayat Log Jurnal Pasien
            </h2>
        </template>

        <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <div>
                        <h3 class="text-base font-extrabold text-slate-800">Log Jurnal Kesehatan Harian</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Daftar seluruh laporan kesehatan yang telah Anda kirimkan.</p>
                    </div>
                </div>

                <!-- Jika Kosong -->
                <div v-if="logs.data.length === 0" class="py-12 text-center text-slate-400 space-y-2">
                    <span class="text-4xl block">📋</span>
                    <p class="text-xs font-semibold">Belum ada jurnal yang pernah dikirim.</p>
                </div>

                <!-- Tabel Log Jurnal -->
                <div v-else class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 font-bold uppercase border-b border-slate-200/60">
                                <th class="py-3 px-4">Waktu Kirim</th>
                                <th class="py-3 px-4">Minum (ml)</th>
                                <th class="py-3 px-4">Tensi</th>
                                <th class="py-3 px-4">Akses Vaskular</th>
                                <th class="py-3 px-4">Keluhan</th>
                                <th class="py-3 px-4">Makanan</th>
                                <th class="py-3 px-4 text-right">Total Kalori</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr v-for="log in logs.data" :key="log.id" class="hover:bg-slate-50/80 transition-all">
                                <td class="py-3 px-4 font-semibold text-slate-700 whitespace-nowrap">
                                    {{ formatDateTime(log.created_at) }}
                                </td>
                                <td class="py-3 px-4 font-bold text-emerald-700 whitespace-nowrap">
                                    {{ log.total_minum }} ml
                                </td>
                                <td class="py-3 px-4 font-mono font-bold text-slate-800 whitespace-nowrap">
                                    {{ log.tekanan_darah || '-' }}
                                </td>
                                <td class="py-3 px-4 whitespace-nowrap">
                                    <span
                                        :class="[
                                            'px-2 py-0.5 rounded text-[10px] font-bold capitalize',
                                            log.kondisi_akses === 'Aman' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700'
                                        ]"
                                    >
                                        {{ log.kondisi_akses }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 whitespace-nowrap">
                                    <div class="flex gap-1">
                                        <span v-if="log.gejala_sesak" class="px-1.5 py-0.5 bg-amber-100 text-amber-800 text-[10px] rounded font-bold">Sesak</span>
                                        <span v-if="log.gejala_kram" class="px-1.5 py-0.5 bg-amber-100 text-amber-800 text-[10px] rounded font-bold">Kram</span>
                                        <span v-if="!log.gejala_sesak && !log.gejala_kram" class="text-slate-400">-</span>
                                    </div>
                                </td>
                                <td class="py-3 px-4">
                                    <button
                                        v-if="log.makanan && log.makanan.length > 0"
                                        @click="selectedLogMakanan = log.makanan"
                                        class="text-sky-600 hover:underline font-bold text-[11px]"
                                    >
                                        Lihat {{ log.makanan.length }} Menu
                                    </button>
                                    <span v-else class="text-slate-400">-</span>
                                </td>
                                <td class="py-3 px-4 font-bold text-slate-800 text-right whitespace-nowrap">
                                    {{ log.total_kalori }} Kal
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Modal Detail Makanan -->
            <div v-if="selectedLogMakanan" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
                <div class="bg-white rounded-2xl max-w-md w-full h-full p-6 space-y-4 shadow-xl">
                    <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                        <h4 class="font-extrabold text-slate-800 text-sm">Detail Menu Makanan</h4>
                        <button @click="selectedLogMakanan = null" class="text-slate-400 hover:text-slate-600 font-bold">×</button>
                    </div>

                    <ul class="space-y-2 max-h-60 overflow-y-auto">
                        <li v-for="(mkn, i) in selectedLogMakanan" :key="i" class="flex justify-between items-center text-xs bg-slate-50 p-2.5 rounded-xl">
                            <div>
                                <p class="font-bold text-slate-800">{{ mkn.name }}</p>
                                <p class="text-[10px] text-slate-500">{{ mkn.porsi }} Porsi x {{ mkn.calories }} Kal</p>
                            </div>
                            <span class="font-black text-emerald-600 text-xs">{{ mkn.subtotal }} Kal</span>
                        </li>
                    </ul>

                    <button
                        @click="selectedLogMakanan = null"
                        class="w-full py-2 bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs rounded-xl"
                    >
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>