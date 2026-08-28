<script setup>
const props = defineProps({
    user: Object,
    stats: {
        type: Object,
        default: () => ({ total_pasien: 0, hd_hari_ini: 0, jurnal_hari_ini: 0, sos_aktif: 0 })
    },
    criticalPatients: {
        type: Array,
        default: () => []
    },
    weeklyData: {
        type: Array,
        default: () => []
    }
});
</script>

<template>
    <div class="space-y-6">
        <!-- Header Petugas -->
        <div class="bg-slate-900 rounded-2xl p-6 text-white shadow-lg flex justify-between items-center relative overflow-hidden">
            <div class="z-10">
                <span class="px-3 py-1 bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 text-xs font-semibold rounded-full">
                    Petugas / Tim Medis HD
                </span>
                <h1 class="text-2xl font-black mt-2">
                    Selamat Tugas, {{ user?.name }}!
                </h1>
                <p class="text-slate-400 text-xs mt-1">
                    Monitoring Pasien Hemodialisa Unit RSUD Ngimbang secara Real-time.
                </p>
            </div>
            <div class="hidden sm:block z-10 text-right">
                <p class="text-xs text-slate-400">Total Pasien Terdaftar</p>
                <p class="text-3xl font-black text-emerald-400">{{ stats.total_pasien }}</p>
            </div>
        </div>

        <!-- STAT CARDS -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase">Pasien HD Hari Ini</p>
                    <h3 class="text-2xl font-black text-slate-800 mt-1">{{ stats.hd_hari_ini }}</h3>
                    <span class="text-[10px] text-emerald-600 font-bold">Data Realtime</span>
                </div>
                <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-rose-200/80 shadow-sm flex items-center justify-between bg-rose-50/30">
                <div>
                    <p class="text-xs font-bold text-rose-600 uppercase">Kondisi Kritis / Merah</p>
                    <h3 class="text-2xl font-black text-rose-700 mt-1">{{ criticalPatients.length }}</h3>
                    <span class="text-[10px] text-rose-600 font-bold">Perlu Tindakan Cepat</span>
                </div>
                <div class="p-3 bg-rose-100 text-rose-600 rounded-xl animate-pulse">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase">Jurnal Diterima</p>
                    <h3 class="text-2xl font-black text-slate-800 mt-1">{{ stats.jurnal_hari_ini }}</h3>
                    <span class="text-[10px] text-slate-500 font-bold">Hari ini</span>
                </div>
                <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase">Sinyal SOS Aktif</p>
                    <h3 class="text-2xl font-black text-emerald-600 mt-1">{{ stats.sos_aktif }}</h3>
                    <span class="text-[10px] text-emerald-600 font-bold">Status Darurat</span>
                </div>
                <div class="p-3 bg-slate-100 text-slate-600 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                </div>
            </div>
        </div>

        <!-- MAIN SECTION -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- DAFTAR PASIEN KRITIS -->
            <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-rose-200 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-rose-100 pb-3">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 bg-rose-600 rounded-full animate-ping"></span>
                        <h3 class="font-extrabold text-base text-rose-900">
                            Daftar Pasien Kondisi Kritis / Merah
                        </h3>
                    </div>
                    <span class="text-xs bg-rose-100 text-rose-700 font-bold px-2.5 py-1 rounded-full">
                        {{ criticalPatients.length }} Pasien
                    </span>
                </div>

                <div v-if="criticalPatients.length > 0" class="space-y-3">
                    <div v-for="p in criticalPatients" :key="p.id" class="p-4 rounded-xl bg-rose-50/60 border border-rose-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <span class="font-black text-sm text-slate-800">{{ p.nama }}</span>
                                <span class="font-mono text-xs text-rose-700 font-bold bg-white px-2 py-0.5 rounded border border-rose-200">{{ p.no_rm }}</span>
                            </div>
                            <p class="text-xs font-bold text-rose-600">⚠️ Status Akses: {{ p.status }}</p>
                            <p class="text-xs text-slate-600">Keluhan: <span class="font-medium text-slate-800">{{ p.keluhan }}</span></p>
                        </div>

                        <div class="flex sm:flex-col items-center sm:items-end justify-between border-t sm:border-t-0 pt-2 sm:pt-0 border-rose-200/50">
                            <div class="text-left sm:text-right">
                                <p class="text-[11px] font-bold text-slate-500">Tensi: <span class="text-rose-700 font-black">{{ p.tensi }}</span></p>
                                <p class="text-[11px] font-bold text-slate-500">Minum: <span class="text-slate-800 font-black">{{ p.minum }}</span></p>
                            </div>
                            <button class="mt-2 px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-lg shadow-sm transition-all">
                                Tindak Lanjuti
                            </button>
                        </div>
                    </div>
                </div>
                <div v-else class="text-center py-8 text-xs text-slate-500 font-semibold">
                    ✅ Tidak ada pasien dalam kondisi kritis saat ini.
                </div>
            </div>

            <!-- GRAFIK MINGGUAN -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
                <div class="border-b border-slate-100 pb-3">
                    <h3 class="font-extrabold text-base text-slate-800">Tren Pasien HD Mingguan</h3>
                    <p class="text-xs text-slate-500">Jumlah pasien menjalani HD per hari</p>
                </div>

                <div v-if="weeklyData.length > 0" class="pt-4 flex items-end justify-between h-48 gap-2">
                    <div v-for="(item, i) in weeklyData" :key="i" class="flex-1 flex flex-col items-center gap-2 h-full justify-end">
                        <span class="text-[10px] font-bold text-slate-600">{{ item.count }}</span>
                        <div class="w-full bg-slate-100 rounded-t-lg relative overflow-hidden flex items-end h-36">
                            <div class="w-full bg-gradient-to-t from-emerald-600 to-teal-500 rounded-t-lg transition-all duration-500" :style="{ height: item.percentage + '%' }"></div>
                        </div>
                        <span class="text-xs font-bold text-slate-500">{{ item.day }}</span>
                    </div>
                </div>
                <div v-else class="text-center py-12 text-xs text-slate-400">
                    Belum ada data mingguan.
                </div>
            </div>
        </div>
    </div>
</template>