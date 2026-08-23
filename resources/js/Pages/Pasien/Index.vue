<script setup>
import { ref, computed } from 'vue';

// State Form Jurnal Kesehatan
const form = ref({
    minum: 500,
    tensi: '120/80',
    akses: 'Aman',
    sesak: false,
    kram: false,
});

// State Jurnal Makanan
const makananDipilih = ref('Nasi Putih');
const porsi = ref(1);
const daftarJurnalMakanan = ref([]);

const activeTab = ref('pasien'); // 'pasien', 'petugas', 'sos'

// Master Data Kalori Makanan Khas Jatim/Lamongan
const databaseKalori = {
    "Nasi Putih": 130,
    "Nasi Jagung": 150,
    "Lontong": 140,
    "Soto Lamongan": 250,
    "Nasi Boranan": 400,
    "Pecel Lele": 250,
    "Tahu Tek": 350,
    "Rawon": 300,
    "Rujak Cingur": 300,
    "Botok": 100,
    "Bandeng Presto": 200,
    "Sayur Asem": 50,
    "Wingko Babat": 150
};

// Total Kalori Computed
const totalKalori = computed(() => {
    return daftarJurnalMakanan.value.reduce((sum, item) => sum + item.kalori, 0);
});

// Method Tambah Makanan
const tambahMakanan = () => {
    const kalPerPorsi = databaseKalori[makananDipilih.value] || 0;
    const totalKal = kalPerPorsi * porsi.value;

    daftarJurnalMakanan.value.push({
        nama: makananDipilih.value,
        porsi: porsi.value,
        kalori: totalKal
    });

    porsi.value = 1;
};

// Method Simpan Laporan
const simpanData = () => {
    let pesanPeringatan = [];

    if (form.value.sesak) {
        pesanPeringatan.push("🫁 MENGALAMI SESAK NAPAS! Petugas disiagakan.");
    }
    if (form.value.akses !== "Aman") {
        pesanPeringatan.push("🩹 ADA MASALAH PADA AKSES CIMINO.");
    }
    if (form.value.minum > 1000) {
        pesanPeringatan.push("💧 Minum melebihi batas (Kurangi kuah-kuahan!).");
    }

    if (pesanPeringatan.length > 0) {
        alert("⚠️ PERHATIAN!\n\n" + pesanPeringatan.join("\n") + "\n\nLaporan masuk ke layar perawat HD RSUD Ngimbang.");
    } else {
        alert("✅ Laporan Berhasil Dikirim!\n\nKesehatan terpantau baik. Sampai jumpa di jadwal berikutnya.");
    }
};

const kirimSOS = () => {
    alert("🚨 SINYAL DARURAT DIKIRIM KE RSUD NGIMBANG!\n\nJangan panik. Lokasi Anda telah terlacak, petugas medis akan segera menghubungi.");
};
</script>

<template>
    <div class="min-h-screen bg-slate-100 flex justify-center items-center p-4 font-['Nunito'] text-slate-700">
        <!-- Phone Frame Wrapper -->
        <div class="w-[380px] h-[85vh] bg-white rounded-[35px] shadow-2xl p-6 overflow-y-auto border-[10px] border-slate-800 relative">
            
            <!-- Header & Logo -->
            <div class="flex flex-col items-center justify-center mb-2">
                <svg class="w-12 h-12 mb-2 drop-shadow-md" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M32 58C46.3594 58 58 46.3594 58 32C58 16.5 45 4 32 4C19 4 6 16.5 6 32C6 46.3594 17.6406 58 32 58Z" fill="#E0F2FE"/>
                    <path d="M44 32C44 40 37 46 32 46C27 46 20 40 20 32C20 23 28 14 32 14C36 14 44 23 44 32Z" fill="#0EA5E9"/>
                    <path d="M32 14C32 14 26 23 26 32C26 36.5 28.5 40 32 42C35.5 40 38 36.5 38 32C38 23 32 14 32 14Z" fill="#0284C7"/>
                    <circle cx="36" cy="24" r="3" fill="#FFFFFF" opacity="0.8"/>
                </svg>
                <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">PANDU <span class="text-sky-600">HD</span></h2>
                <span class="text-xs font-bold text-slate-500 uppercase tracking-widest mt-1 mb-4">RSUD Ngimbang</span>
            </div>

            <!-- Tab Navigation -->
            <div class="flex justify-between bg-slate-100 p-1 rounded-2xl mb-6 shadow-inner">
                <button 
                    @click="activeTab = 'pasien'"
                    :class="['flex-1 py-2.5 text-xs font-bold rounded-xl transition-all', activeTab === 'pasien' ? 'bg-white text-sky-600 shadow-sm' : 'text-slate-500']"
                >
                    👤 Pasien
                </button>
                <button 
                    @click="activeTab = 'petugas'"
                    :class="['flex-1 py-2.5 text-xs font-bold rounded-xl transition-all', activeTab === 'petugas' ? 'bg-white text-sky-600 shadow-sm' : 'text-slate-500']"
                >
                    👨‍⚕️ Petugas
                </button>
                <button 
                    @click="activeTab = 'sos'"
                    :class="['flex-1 py-2.5 text-xs font-bold rounded-xl transition-all', activeTab === 'sos' ? 'bg-red-100 text-red-700' : 'text-red-500']"
                >
                    🚨 Darurat
                </button>
            </div>

            <!-- TAB 1: PASIEN -->
            <div v-if="activeTab === 'pasien'" class="space-y-4 transition-all duration-300">
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Halo, Bapak Budi 👋</h3>
                    <p class="text-xs text-slate-500">Semoga hari ini menyenangkan. Mari isi jurnal kesehatan.</p>
                </div>

                <!-- Card Pemantauan Cairan -->
                <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm space-y-3">
                    <div class="font-extrabold text-slate-900 text-sm flex items-center gap-2">💧 Pemantauan Cairan</div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Total Minum Hari Ini (ml):</label>
                        <input v-model.number="form.minum" type="number" class="w-full p-3 bg-slate-50 border-2 border-slate-200 rounded-xl font-bold focus:outline-none focus:border-sky-500 focus:bg-white text-slate-700">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Tekanan Darah:</label>
                        <input v-model="form.tensi" type="text" placeholder="120/80" class="w-full p-3 bg-slate-50 border-2 border-slate-200 rounded-xl font-bold focus:outline-none focus:border-sky-500 focus:bg-white text-slate-700">
                    </div>
                </div>

                <!-- Card Kondisi Fisik -->
                <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm space-y-3">
                    <div class="font-extrabold text-slate-900 text-sm flex items-center gap-2">🩺 Kondisi Fisik</div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Kondisi Selang/Cimino:</label>
                        <select v-model="form.akses" class="w-full p-3 bg-slate-50 border-2 border-slate-200 rounded-xl font-bold focus:outline-none focus:border-sky-500 focus:bg-white text-slate-700">
                            <option value="Aman">✅ Aman, Desir Kuat</option>
                            <option value="Bengkak">⚠️ Bengkak / Merah</option>
                            <option value="Nyeri">❌ Nyeri / Keluar Darah</option>
                        </select>
                    </div>

                    <label class="block text-[11px] font-bold text-slate-500 uppercase mt-2">Keluhan Hari Ini (Centang):</label>
                    <div class="flex items-center bg-slate-50 p-3 rounded-xl border border-slate-200">
                        <input id="sesak" v-model="form.sesak" type="checkbox" class="w-5 h-5 accent-sky-600 rounded cursor-pointer mr-3">
                        <label for="sesak" class="text-xs font-semibold text-slate-800 cursor-pointer">Sesak Napas saat tidur</label>
                    </div>
                    <div class="flex items-center bg-slate-50 p-3 rounded-xl border border-slate-200">
                        <input id="kram" v-model="form.kram" type="checkbox" class="w-5 h-5 accent-sky-600 rounded cursor-pointer mr-3">
                        <label for="kram" class="text-xs font-semibold text-slate-800 cursor-pointer">Kram Otot / Gatal</label>
                    </div>
                </div>

                <!-- Card Jurnal Makanan -->
                <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm space-y-3">
                    <div class="font-extrabold text-slate-900 text-sm flex items-center gap-2">🍛 Jurnal Makanan</div>
                    <span class="text-[11px] text-red-500 font-bold block">*Awas: Kuah soto/rawon dihitung sebagai cairan minum!</span>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Pilih Menu (Khas Lamongan/Jatim):</label>
                        <select v-model="makananDipilih" class="w-full p-3 bg-slate-50 border-2 border-slate-200 rounded-xl font-bold text-xs focus:outline-none focus:border-sky-500">
                            <optgroup label="Sumber Karbohidrat">
                                <option value="Nasi Putih">🍚 Nasi Putih - 1 Centong (130 Kal)</option>
                                <option value="Nasi Jagung">🌽 Nasi Jagung - 1 Centong (150 Kal)</option>
                                <option value="Lontong">🍙 Lontong - 1 Buah (140 Kal)</option>
                            </optgroup>
                            <optgroup label="Menu Spesial Lamongan & Jatim">
                                <option value="Soto Lamongan">🍲 Soto Lamongan Daging Saja (250 Kal)</option>
                                <option value="Nasi Boranan">🍛 Nasi Boranan Porsi Sedang (400 Kal)</option>
                                <option value="Pecel Lele">🐟 Pecel Lele Tanpa Nasi (250 Kal)</option>
                                <option value="Tahu Tek">🧆 Tahu Tek / Tahu Telur (350 Kal)</option>
                                <option value="Rawon">🥘 Rawon Daging Tanpa Kuah Banyak (300 Kal)</option>
                                <option value="Rujak Cingur">🥗 Rujak Cingur (300 Kal)</option>
                            </optgroup>
                            <optgroup label="Lauk, Sayur & Jajanan">
                                <option value="Botok">🥥 Botok Teri/Tahu (100 Kal)</option>
                                <option value="Bandeng Presto">🐟 Bandeng Presto - 1 Potong (200 Kal)</option>
                                <option value="Sayur Asem">🥣 Sayur Asem - Kuah Sedikit (50 Kal)</option>
                                <option value="Wingko Babat">🍘 Wingko Babat - 1 Potong Kecil (150 Kal)</option>
                            </optgroup>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Jumlah Porsi:</label>
                        <input v-model.number="porsi" type="number" min="1" class="w-full p-3 bg-slate-50 border-2 border-slate-200 rounded-xl font-bold focus:outline-none">
                    </div>

                    <button @click="tambahMakanan" class="w-full py-3 bg-sky-100 text-sky-700 font-bold rounded-xl text-xs hover:bg-sky-200 transition-colors">
                        + Tambah ke Jurnal
                    </button>

                    <ul class="space-y-1 text-xs text-slate-600">
                        <li v-for="(item, idx) in daftarJurnalMakanan" :key="idx" class="bg-slate-100 p-2 rounded-lg font-bold">
                            {{ item.porsi }}x {{ item.nama }} ({{ item.kalori }} Kal)
                        </li>
                    </ul>

                    <div class="text-right text-sky-700 font-black text-base bg-sky-50 p-3 rounded-xl">
                        Total: <span>{{ totalKalori }}</span> Kal
                    </div>
                </div>

                <button @click="simpanData" class="w-full py-4 bg-gradient-to-r from-sky-500 to-sky-700 text-white font-extrabold text-sm rounded-2xl shadow-lg shadow-sky-500/30 active:scale-[0.98] transition-transform">
                    KIRIM LAPORAN HARI INI
                </button>
            </div>

            <!-- TAB 2: RINGKASAN PETUGAS (DARI MOBILE) -->
            <div v-if="activeTab === 'petugas'" class="space-y-3">
                <h3 class="font-bold text-slate-900">Pantauan Petugas HD</h3>
                <div class="p-4 bg-rose-50 border-l-4 border-red-500 rounded-r-xl">
                    <p class="text-xs text-slate-800 space-y-1">
                        <strong>Budi (08:00 WIB)</strong><br>
                        Tensi: <span class="text-red-600 font-bold">160/100</span> | Cairan: 1200 ml<br>
                        Akses: <span class="text-red-600 font-bold">Bengkak</span><br>
                        <span class="inline-block mt-2 px-2 py-1 bg-red-200 text-red-800 text-[10px] font-extrabold rounded">TINDAKAN SEGERA</span>
                    </p>
                </div>
            </div>

            <!-- TAB 3: SOS EMERGENCY -->
            <div v-if="activeTab === 'sos'" class="text-center py-6">
                <svg class="w-20 h-20 text-red-500 mx-auto mb-2 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <h3 class="text-red-700 text-xl font-extrabold mb-2">DARURAT MEDIS</h3>
                <p class="text-xs text-slate-500 font-semibold mb-6">Tekan tombol di bawah untuk memanggil bantuan medis RSUD Ngimbang.</p>
                <button @click="kirimSOS" class="w-full py-5 bg-gradient-to-r from-red-500 to-red-700 text-white font-extrabold text-base rounded-2xl shadow-xl shadow-red-500/40 active:scale-[0.98]">
                    BANTU SAYA SEKARANG
                </button>
            </div>

        </div>
    </div>
</template>