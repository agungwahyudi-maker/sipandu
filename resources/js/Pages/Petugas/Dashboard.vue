<script setup>
import { ref } from 'vue';

// Sample Data Pasien Aktif untuk Dashboard Petugas
const kpiData = ref({
    sosAktif: 1,
    antrian: 24,
    stabil: 23
});

const daftarPasien = ref([
    {
        id: 1,
        nama: 'Bpk. Andi',
        tensi: '180/100',
        status: 'SOS: SESAK',
        statusType: 'sos',
        noHP: '628123456789',
        keluhan: 'Kondisi Sesak Napas Berat'
    },
    {
        id: 2,
        nama: 'Ibu Siti',
        tensi: '140/90',
        status: 'PERLU PANTAU',
        statusType: 'warning',
        noHP: '628987654321',
        keluhan: 'Kenaikan Berat Badan melebihi batas'
    }
]);

// Fungsi Chat via WhatsApp Web/App API
const bukaWhatsApp = (namaPasien, noHP, status) => {
    const pesan = `Halo, saya perawat dari Ruang HD RSUD Ngimbang. Saya ingin menginformasikan kondisi ${namaPasien} saat ini: ${status}. Mohon segera merespons.`;
    const url = `https://wa.me/${noHP}?text=${encodeURIComponent(pesan)}`;
    window.open(url, '_blank');
};
</script>

<template>
    <div class="min-h-screen bg-slate-50 p-6 text-slate-800 font-['Inter']">
        <div class="max-w-5xl mx-auto space-y-6">
            
            <!-- Header Dashboard -->
            <div class="flex justify-between items-center pb-4 border-b border-slate-200">
                <div class="flex items-center gap-3">
                    <h2 class="text-xl font-bold text-slate-900">PANDU HD Petugas</h2>
                    <span class="text-xs bg-sky-100 text-sky-800 px-3 py-1 rounded-full font-bold">RSUD Ngimbang</span>
                </div>
            </div>

            <!-- KPI Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div class="bg-white p-5 rounded-2xl shadow-sm border-l-4 border-red-500">
                    <div class="text-xs text-slate-500 font-semibold tracking-wider uppercase">SOS AKTIF</div>
                    <div class="text-3xl font-black text-slate-900 mt-1">{{ kpiData.sosAktif }}</div>
                </div>
                <div class="bg-white p-5 rounded-2xl shadow-sm border-l-4 border-sky-600">
                    <div class="text-xs text-slate-500 font-semibold tracking-wider uppercase">ANTRIAN</div>
                    <div class="text-3xl font-black text-slate-900 mt-1">{{ kpiData.antrian }}</div>
                </div>
                <div class="bg-white p-5 rounded-2xl shadow-sm border-l-4 border-emerald-500">
                    <div class="text-xs text-slate-500 font-semibold tracking-wider uppercase">STABIL</div>
                    <div class="text-3xl font-black text-slate-900 mt-1">{{ kpiData.stabil }}</div>
                </div>
            </div>

            <!-- Tabel Monitoring Pasien -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                            <th class="p-4">Pasien</th>
                            <th class="p-4">Tensi</th>
                            <th class="p-4">Status</th>
                            <th class="p-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        <tr v-for="pasien in daftarPasien" :key="pasien.id" class="hover:bg-slate-50/50 transition-colors">
                            <td class="p-4 font-bold text-slate-900">{{ pasien.nama }}</td>
                            <td class="p-4 font-medium text-slate-700">{{ pasien.tensi }}</td>
                            <td class="p-4">
                                <span :class="[
                                    'px-3 py-1 rounded-full text-xs font-bold inline-block',
                                    pasien.statusType === 'sos' ? 'bg-red-100 text-red-800' : 'bg-amber-100 text-amber-800'
                                ]">
                                    {{ pasien.status }}
                                </span>
                            </td>
                            <td class="p-4 text-center space-x-2">
                                <button 
                                    v-if="pasien.statusType === 'sos'"
                                    class="px-3 py-1.5 bg-red-700 hover:bg-red-800 text-white rounded-lg text-xs font-bold transition-colors"
                                >
                                    Tindak
                                </button>
                                <button 
                                    v-else 
                                    class="px-3 py-1.5 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-lg text-xs font-bold transition-colors"
                                >
                                    Detail
                                </button>

                                <button 
                                    @click="bukaWhatsApp(pasien.nama, pasien.noHP, pasien.keluhan)"
                                    class="px-3 py-1.5 bg-emerald-100 hover:bg-emerald-200 text-emerald-800 rounded-lg text-xs font-bold transition-colors inline-flex items-center gap-1"
                                >
                                    💬 Chat Keluarga
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</template>