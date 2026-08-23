<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    user: Object,
});

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    role: props.user.role,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.put(route('users.update', props.user.id), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Edit User - SIKEMBANG" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-extrabold text-xl text-slate-800 leading-tight">
                        Edit Data User
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">
                        Perbarui informasi pengguna atau ubah hak akses sistem
                    </p>
                </div>

                <Link
                    :href="route('users.index')"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl transition-all"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Kembali</span>
                </Link>
            </div>
        </template>

        <div class="max-w-3xl mx-auto">
            <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/80 shadow-sm">
                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Nama Pengguna -->
                    <div>
                        <InputLabel for="name" value="Nama Lengkap" />
                        <TextInput
                            id="name"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.name"
                            required
                            autofocus
                        />
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

                    <!-- Email -->
                    <div>
                        <InputLabel for="email" value="Alamat Email" />
                        <TextInput
                            id="email"
                            type="email"
                            class="mt-1 block w-full"
                            v-model="form.email"
                            required
                        />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <!-- Role / Hak Akses -->
                    <div>
                        <InputLabel for="role" value="Role / Hak Akses" />
                        <select
                            id="role"
                            v-model="form.role"
                            class="mt-1 block w-full border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm text-sm"
                            required
                        >
                            <option value="pegawai">Pegawai / Perawat</option>
                            <option value="petugas">Petugas HD</option>
                            <option value="admin">Administrator</option>
                        </select>
                        <InputError class="mt-2" :message="form.errors.role" />
                    </div>

                    <!-- Informasi Ubah Password -->
                    <div class="pt-4 border-t border-slate-100">
                        <p class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Ubah Password (Opsional)
                        </p>
                        <p class="text-xs text-slate-400 mb-4">
                            Biarkan kosong jika tidak ingin mengubah password user ini.
                        </p>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Password Baru -->
                            <div>
                                <InputLabel for="password" value="Password Baru" />
                                <TextInput
                                    id="password"
                                    type="password"
                                    class="mt-1 block w-full"
                                    v-model="form.password"
                                    autocomplete="new-password"
                                />
                                <InputError class="mt-2" :message="form.errors.password" />
                            </div>

                            <!-- Konfirmasi Password -->
                            <div>
                                <InputLabel for="password_confirmation" value="Konfirmasi Password Baru" />
                                <TextInput
                                    id="password_confirmation"
                                    type="password"
                                    class="mt-1 block w-full"
                                    v-model="form.password_confirmation"
                                    autocomplete="new-password"
                                />
                                <InputError class="mt-2" :message="form.errors.password_confirmation" />
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <Link
                            :href="route('users.index')"
                            class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl transition-all"
                        >
                            Batal
                        </Link>

                        <PrimaryButton
                            class="bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                        >
                            Simpan Perubahan
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>