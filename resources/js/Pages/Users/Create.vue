<script setup>
import { useForm, Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const form = useForm({
    // Account Info
    name: '',
    email: '',
    role: 'pasien',
    password: '',
    password_confirmation: '',

    // Pasien HD Info
    no_rm: '',
    nik: '',
    jenis_kelamin: 'L',
    tempat_lahir: '',
    tanggal_lahir: '',
    alamat: '',
    no_hp_pasien: '',
    no_hp_keluarga: '',
    jenis_akses: '',
    posisi_akses: '',
    berat_badan_kering: '',
    jadwal_hd: '',
    sesi_hd: '',
    status_aktif: 'aktif',
});

const submit = () => {
    form.post(route('users.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Tambah User & Pasien - SIKEMBANG" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-extrabold text-xl text-slate-800 leading-tight">
                        Tambah User / Pasien Baru
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">
                        Registrasi akun pengguna dan data medis Hemodialisa
                    </p>
                </div>
                <Link
                    :href="route('users.index')"
                    class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl transition-all"
                >
                    Kembali
                </Link>
            </div>
        </template>

        <form @submit.prevent="submit" class="space-y-6 max-w-5xl mx-auto pb-12">
            <!-- Section 1: Informasi Akun -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
                <h3 class="text-sm font-extrabold text-slate-800 border-b border-slate-100 pb-3 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    Informasi Akun Utama
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Role Select -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Role / Hak Akses</label>
                        <select
                            v-model="form.role"
                            class="w-full py-2.5 px-3.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                        >
                            <option value="pasien">Pasien HD</option>
                            <option value="petugas">Perawat / Petugas HD</option>
                        </select>
                        <span v-if="form.errors.role" class="text-rose-500 text-[11px] mt-1">{{ form.errors.role }}</span>
                    </div>

                    <!-- Nama Lengkap -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap</label>
                        <input
                            v-model="form.name"
                            type="text"
                            placeholder="Masukkan nama lengkap beserta gelar jika ada"
                            class="w-full py-2.5 px-3.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                        />
                        <span v-if="form.errors.name" class="text-rose-500 text-[11px] mt-1">{{ form.errors.name }}</span>
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Email (Username Login)</label>
                        <input
                            v-model="form.email"
                            type="email"
                            placeholder="contoh@rsudngimbang.id"
                            class="w-full py-2.5 px-3.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                        />
                        <span v-if="form.errors.email" class="text-rose-500 text-[11px] mt-1">{{ form.errors.email }}</span>
                    </div>

                    <!-- Password Fields -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Password</label>
                        <input
                            v-model="form.password"
                            type="password"
                            placeholder="Minimal 8 karakter"
                            class="w-full py-2.5 px-3.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                        />
                        <span v-if="form.errors.password" class="text-rose-500 text-[11px] mt-1">{{ form.errors.password }}</span>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Konfirmasi Password</label>
                        <input
                            v-model="form.password_confirmation"
                            type="password"
                            placeholder="Ulangi password"
                            class="w-full py-2.5 px-3.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                        />
                    </div>
                </div>
            </div>

            <!-- Section 2: Informasi Medis Pasien (Hanya muncul jika Role = Pasien) -->
            <div v-if="form.role === 'pasien'" class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
                <h3 class="text-sm font-extrabold text-slate-800 border-b border-slate-100 pb-3 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-sky-500"></span>
                    Detail Rekam Medis Pasien Hemodialisa (HD)
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <!-- No RM & NIK -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">No. Rekam Medis (RM) *</label>
                        <input
                            v-model="form.no_rm"
                            type="text"
                            placeholder="Contoh: 12-34-56"
                            class="w-full py-2.5 px-3.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 font-mono"
                        />
                        <span v-if="form.errors.no_rm" class="text-rose-500 text-[11px] mt-1">{{ form.errors.no_rm }}</span>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">NIK (KTP) *</label>
                        <input
                            v-model="form.nik"
                            type="text"
                            maxlength="16"
                            placeholder="3524..."
                            class="w-full py-2.5 px-3.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                        />
                        <span v-if="form.errors.nik" class="text-rose-500 text-[11px] mt-1">{{ form.errors.nik }}</span>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Jenis Kelamin</label>
                        <select
                            v-model="form.jenis_kelamin"
                            class="w-full py-2.5 px-3.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                        >
                            <option value="L">Laki-Laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>

                    <!-- Tempat & Tgl Lahir -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Tempat Lahir</label>
                        <input
                            v-model="form.tempat_lahir"
                            type="text"
                            placeholder="Kota Lahir"
                            class="w-full py-2.5 px-3.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700"
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Lahir</label>
                        <input
                            v-model="form.tanggal_lahir"
                            type="date"
                            class="w-full py-2.5 px-3.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700"
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Berat Badan Kering (kg)</label>
                        <input
                            v-model="form.berat_badan_kering"
                            type="number"
                            step="0.1"
                            placeholder="Contoh: 55.5"
                            class="w-full py-2.5 px-3.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700"
                        />
                    </div>

                    <!-- Kontak -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">No. HP Pasien</label>
                        <input
                            v-model="form.no_hp_pasien"
                            type="text"
                            placeholder="0812..."
                            class="w-full py-2.5 px-3.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700"
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">No. HP Keluarga / Penanggung Jawab</label>
                        <input
                            v-model="form.no_hp_keluarga"
                            type="text"
                            placeholder="0812..."
                            class="w-full py-2.5 px-3.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700"
                        />
                    </div>

                    <!-- Jadwal & Akses Vaskular -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Jadwal Hemodialisa</label>
                        <select
                            v-model="form.jadwal_hd"
                            class="w-full py-2.5 px-3.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700"
                        >
                            <option value="">-- Pilih Jadwal --</option>
                            <option value="senin_kamis">Senin - Kamis</option>
                            <option value="selasa_jumat">Selasa - Jumat</option>
                            <option value="rabu_sabtu">Rabu - Sabtu</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Sesi HD</label>
                        <select
                            v-model="form.sesi_hd"
                            class="w-full py-2.5 px-3.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700"
                        >
                            <option value="">-- Pilih Sesi --</option>
                            <option value="Pagi">Pagi</option>
                            <option value="Siang">Siang</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Jenis Akses Vaskular</label>
                        <input
                            v-model="form.jenis_akses"
                            type="text"
                            placeholder="AV Fistula / CDL / Femoral"
                            class="w-full py-2.5 px-3.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700"
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Posisi Akses</label>
                        <input
                            v-model="form.posisi_akses"
                            type="text"
                            placeholder="Lengan Kiri / Lengan Kanan / Femoral Kanan"
                            class="w-full py-2.5 px-3.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700"
                        />
                    </div>

                    <!-- Alamat Lengkap -->
                    <div class="sm:col-span-3">
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Alamat Lengkap Pasien</label>
                        <textarea
                            v-model="form.alamat"
                            rows="2"
                            placeholder="Jl. Raya No. XX, Desa, Kecamatan, Kabupaten"
                            class="w-full py-2.5 px-3.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700"
                        ></textarea>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end gap-3">
                <Link
                    :href="route('users.index')"
                    class="px-5 py-2.5 p-3 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition-all"
                >
                    Batal
                </Link>
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="px-5 py-2.5 p-3 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-xs font-semibold rounded-xl shadow-md transition-all disabled:opacity-50"
                >
                    Simpan
                </button>
            </div>
        </form>
    </AuthenticatedLayout>
</template>