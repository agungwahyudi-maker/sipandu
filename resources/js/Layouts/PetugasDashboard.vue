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

const formatWhatsappUrl = (noHp, nama, status, keluhan) => {
    if (!noHp) return '#';

    // 1. Bersihkan karakter non-angka (spasi, strip, dll)
    let cleaned = String(noHp).replace(/\D/g, '');

    // 2. Format dari 08xx menjadi 628xx
    if (cleaned.startsWith('0')) {
        cleaned = '62' + cleaned.slice(1);
    }

    // 3. Susun teks pesan otomatis untuk pasien
    const text = `Halo Bpk/Ibu ${nama},\n\nKami dari *Tim Medis Unit Hemodialisa RSUD Ngimbang* memantau kondisi Anda melalui aplikasi PANDU HD.\n\n` +
                 `*Catatan Pemantauan:* \n` +
                 `- Status Akses: ${status}\n` +
                 `- Keluhan: ${keluhan}\n\n` +
                 `Mohon konfirmasi kondisi Anda saat ini. Apakah membutuhkan bantuan atau penanganan medis segera?`;

    // 4. Encode URL
    return `https://wa.me/${cleaned}?text=${encodeURIComponent(text)}`;
};
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

                            <a 
                                :href="formatWhatsappUrl(p.no_hp_pasien, p.nama, p.status, p.keluhan)" 
                                target="_blank" 
                                rel="noopener noreferrer"
                                class="mt-2 inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-lg shadow-sm transition-all"
                            >
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.105 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.572-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                </svg>
                                Tindak Lanjuti via WA
                            </a>
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