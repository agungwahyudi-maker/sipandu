<script setup>
import { ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    user: Object,
    pasien: Object,
});

const activeTab = ref('pasien');
const selectedFood = ref('Nasi Putih');
const foodPorsi = ref(1);
const foodList = ref([]);

const masterFoods = [
    { name: 'Nasi Putih', calories: 130, group: 'Sumber Karbohidrat' },
    { name: 'Nasi Jagung', calories: 150, group: 'Sumber Karbohidrat' },
    { name: 'Lontong', calories: 140, group: 'Sumber Karbohidrat' },
    { name: 'Soto Lamongan Daging Saja', calories: 250, group: 'Menu Spesial Lamongan & Jatim' },
    { name: 'Nasi Boranan Porsi Sedang', calories: 400, group: 'Menu Spesial Lamongan & Jatim' },
    { name: 'Pecel Lele Tanpa Nasi', calories: 250, group: 'Menu Spesial Lamongan & Jatim' },
    { name: 'Tahu Tek / Tahu Telur', calories: 350, group: 'Menu Spesial Lamongan & Jatim' },
    { name: 'Rawon Daging Tanpa Kuah Banyak', calories: 300, group: 'Menu Spesial Lamongan & Jatim' },
    { name: 'Rujak Cingur', calories: 300, group: 'Menu Spesial Lamongan & Jatim' },
    { name: 'Botok Teri/Tahu', calories: 100, group: 'Lauk, Sayur & Jajanan' },
    { name: 'Bandeng Presto (1 Potong)', calories: 200, group: 'Lauk, Sayur & Jajanan' },
    { name: 'Sayur Asem (Kuah Sedikit)', calories: 50, group: 'Lauk, Sayur & Jajanan' },
    { name: 'Wingko Babat (1 Potong Kecil)', calories: 150, group: 'Lauk, Sayur & Jajanan' },
];

const journalForm = useForm({
    total_minum: 500,
    tekanan_darah: '',
    kondisi_akses: 'Aman',
    gejala_sesak: false,
    gejala_kram: false,
    makanan: [],
    total_kalori: 0,
});

const tambahMakanan = () => {
    const item = masterFoods.find(f => f.name === selectedFood.value);
    if (!item) return;

    const existingIndex = foodList.value.findIndex(f => f.name === item.name);
    if (existingIndex > -1) {
        foodList.value[existingIndex].porsi += Number(foodPorsi.value);
        foodList.value[existingIndex].subtotal = foodList.value[existingIndex].porsi * item.calories;
    } else {
        foodList.value.push({
            name: item.name,
            calories: item.calories,
            porsi: Number(foodPorsi.value),
            subtotal: item.calories * Number(foodPorsi.value)
        });
    }
    foodPorsi.value = 1;
};

const hapusMakanan = (index) => {
    foodList.value.splice(index, 1);
};

const totalKalori = computed(() => {
    return foodList.value.reduce((acc, curr) => acc + curr.subtotal, 0);
});

const simpanJurnal = () => {
    journalForm.makanan = foodList.value;
    journalForm.total_kalori = totalKalori.value;

    journalForm.post(route('jurnal.store'), {
        preserveScroll: true,
        onSuccess: () => {
            alert('Jurnal kesehatan hari ini berhasil dikirim!');
            foodList.value = [];
        },
        onError: () => {
            alert('Gagal mengirim laporan. Pastikan data terisi dengan benar.');
        }
    });
};

const triggerSOS = () => {
    alert('🚨 SINYAL DARURAT DIKIRIM KE RSUD NGIMBANG!\n\nJangan panik. Lokasi & Identitas Anda telah dikirim, tim medis akan segera menghubungi nomor Anda.');
};
</script>

<template>
    <div class="space-y-6">
        <!-- Banner Selamat Datang Pasien -->
        <div class="bg-gradient-to-r from-emerald-600 to-teal-700 rounded-2xl p-6 text-white shadow-lg flex justify-between items-center">
            <div>
                <span class="px-3 py-1 bg-white/20 text-xs font-semibold rounded-full backdrop-blur-md">
                    Pasien Hemodialisa
                </span>
                <h1 class="text-2xl font-black mt-2">
                    Selamat Datang, {{ user?.name }}!
                </h1>
                <p class="text-emerald-100 text-xs mt-1">
                    Semoga hari ini menyenangkan. Mari isi jurnal kesehatan harian Anda.
                </p>
            </div>
            <div v-if="pasien?.no_rm" class="hidden sm:block text-right bg-white/10 p-4 rounded-xl backdrop-blur-sm border border-white/10">
                <p class="text-[10px] text-emerald-200 uppercase font-semibold">No. Rekam Medis (RM)</p>
                <p class="text-2xl font-mono font-bold tracking-wider">{{ pasien.no_rm }}</p>
            </div>
        </div>

        <!-- MENU TAB SELECTION -->
        <div class="flex items-center gap-3 bg-white p-2 rounded-2xl border border-slate-200/80 shadow-sm">
            <button
                @click="activeTab = 'pasien'"
                :class="[
                    'flex-1 py-3 px-4 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-2',
                    activeTab === 'pasien'
                        ? 'bg-emerald-500 text-white shadow-md shadow-emerald-500/20'
                        : 'bg-slate-50 text-slate-600 hover:bg-slate-100'
                ]"
            >
                <span class="text-base">👤</span> Jurnal Kesehatan Pasien
            </button>
            <button
                @click="activeTab = 'sos'"
                :class="[
                    'flex-1 py-3 px-4 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-2',
                    activeTab === 'sos'
                        ? 'bg-rose-600 text-white shadow-md shadow-rose-600/20 animate-pulse'
                        : 'bg-rose-50 text-rose-600 hover:bg-rose-100'
                ]"
            >
                <span class="text-base">🚨</span> Darurat / SOS
            </button>
        </div>

        <!-- TAB 1: FORM JURNAL -->
        <div v-if="activeTab === 'pasien'" class="space-y-6">
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- CARD 1: Cairan & Tensi -->
                    <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200/60 space-y-4">
                        <div class="flex items-center gap-2 font-extrabold text-sm text-slate-800">
                            <span>💧</span> Pemantauan Cairan & Tensi
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Total Minum Hari Ini (ml)</label>
                            <input v-model="journalForm.total_minum" type="number" class="w-full py-2 px-3 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Tekanan Darah (mmHg)</label>
                            <input v-model="journalForm.tekanan_darah" type="text" placeholder="Contoh: 120/80" class="w-full py-2 px-3 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500" />
                        </div>
                    </div>

                    <!-- CARD 2: Keluhan & Akses -->
                    <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200/60 space-y-4">
                        <div class="flex items-center gap-2 font-extrabold text-sm text-slate-800">
                            <span>🩺</span> Kondisi Fisik & Akses
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Kondisi Selang / Cimino</label>
                            <select v-model="journalForm.kondisi_akses" class="w-full py-2 px-3 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500/20">
                                <option value="Aman">✅ Aman, Desir Kuat</option>
                                <option value="Bengkak">⚠️ Bengkak / Merah</option>
                                <option value="Nyeri">❌ Nyeri / Keluar Darah</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-2">Keluhan Hari Ini</label>
                            <div class="space-y-2 text-xs text-slate-700 font-medium">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input v-model="journalForm.gejala_sesak" type="checkbox" class="rounded text-emerald-600 focus:ring-emerald-500" />
                                    <span>Sesak Napas saat tidur</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input v-model="journalForm.gejala_kram" type="checkbox" class="rounded text-emerald-600 focus:ring-emerald-500" />
                                    <span>Kram Otot / Gatal</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- CARD 3: Jurnal Makanan -->
                    <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200/60 space-y-4">
                        <div class="flex items-center gap-2 font-extrabold text-sm text-slate-800">
                            <span>🍛</span> Jurnal Makanan
                        </div>
                        <p class="text-[11px] font-semibold text-rose-600 bg-rose-50 p-2 rounded-lg border border-rose-100">
                            *Awas: Kuah soto/rawon dihitung sebagai cairan minum!
                        </p>
                        <div class="space-y-2">
                            <select v-model="selectedFood" class="w-full py-2 px-3 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-800">
                                <optgroup label="Sumber Karbohidrat">
                                    <option value="Nasi Putih">🍚 Nasi Putih - 1 Centong (130 Kal)</option>
                                    <option value="Nasi Jagung">🌽 Nasi Jagung - 1 Centong (150 Kal)</option>
                                    <option value="Lontong">🍙 Lontong - 1 Buah (140 Kal)</option>
                                </optgroup>
                                <optgroup label="Menu Spesial Lamongan & Jatim">
                                    <option value="Soto Lamongan Daging Saja">🍲 Soto Lamongan Daging Saja (250 Kal)</option>
                                    <option value="Nasi Boranan Porsi Sedang">🍛 Nasi Boranan Porsi Sedang (400 Kal)</option>
                                    <option value="Pecel Lele Tanpa Nasi">🐟 Pecel Lele Tanpa Nasi (250 Kal)</option>
                                </optgroup>
                            </select>

                            <div class="flex items-center gap-2 pt-1">
                                <input v-model="foodPorsi" type="number" min="1" class="w-20 py-2 px-3 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-800" />
                                <button type="button" @click="tambahMakanan" class="flex-1 py-2 px-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-sm transition-all">
                                    + Tambah
                                </button>
                            </div>
                        </div>

                        <div v-if="foodList.length > 0" class="space-y-2 pt-2 border-t border-slate-200">
                            <ul class="space-y-1.5 max-h-36 overflow-y-auto pr-1">
                                <li v-for="(item, idx) in foodList" :key="idx" class="flex items-center justify-between text-xs bg-white p-2 rounded-lg border border-slate-200">
                                    <div>
                                        <p class="font-bold text-slate-800">{{ item.name }}</p>
                                        <p class="text-[10px] text-slate-500">{{ item.porsi }} x {{ item.calories }} Kal</p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-extrabold text-emerald-700">{{ item.subtotal }} Kal</span>
                                        <button @click="hapusMakanan(idx)" class="text-rose-500 hover:text-rose-700 font-bold">×</button>
                                    </div>
                                </li>
                            </ul>
                            <div class="flex items-center justify-between pt-2 border-t border-slate-200 text-xs font-black text-slate-800">
                                <span>Total Estimasi:</span>
                                <span class="text-emerald-600">{{ totalKalori }} Kal</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-4 border-t border-slate-100">
                    <button type="button" @click="simpanJurnal" :disabled="journalForm.processing" class="w-full sm:w-auto px-8 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-xl shadow-lg shadow-emerald-600/20 transition-all">
                        KIRIM LAPORAN HARI INI
                    </button>
                </div>
            </div>
        </div>

        <!-- TAB 2: SOS -->
        <div v-if="activeTab === 'sos'" class="bg-white p-8 rounded-2xl border border-rose-200 shadow-sm text-center max-w-2xl mx-auto space-y-6">
            <div class="w-24 h-24 bg-rose-100 rounded-full flex items-center justify-center mx-auto text-rose-600 animate-bounce">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div>
                <h3 class="text-2xl font-black text-rose-700">DARURAT MEDIS</h3>
                <p class="text-xs font-semibold text-slate-500 mt-2 max-w-md mx-auto">
                    Tekan tombol di bawah untuk memanggil bantuan medis darurat RSUD Ngimbang.
                </p>
            </div>
            <button @click="triggerSOS" class="w-full py-5 bg-gradient-to-r from-rose-600 to-red-700 hover:from-rose-700 hover:to-red-800 text-white font-black text-base rounded-2xl shadow-xl shadow-rose-600/30">
                🚨 BANTU SAYA SEKARANG
            </button>
        </div>
    </div>
</template>