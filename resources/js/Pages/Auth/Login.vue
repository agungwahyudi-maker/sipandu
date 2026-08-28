<script setup>
import { computed } from 'vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    login: '',
    password: '',
    remember: false,
});

// Deteksi otomatis: tampilkan input password HANYA jika memasukkan Email
const isEmailInput = computed(() => {
    return form.login.includes('@');
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Log In - PANDU HD" />

    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-emerald-900 via-slate-900 to-teal-950 p-4 sm:p-6 relative overflow-hidden selection:bg-emerald-500 selection:text-white">
        <!-- Background Animated Glow Balls -->
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-emerald-500/20 rounded-full blur-3xl animate-pulse pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-teal-500/20 rounded-full blur-3xl animate-pulse delay-1000 pointer-events-none"></div>

        <!-- Main Login Card Container -->
        <div class="w-full max-w-md bg-white/95 backdrop-blur-md rounded-2xl shadow-2xl border border-white/20 p-8 sm:p-10 transition-all duration-300 relative z-10 hover:shadow-emerald-900/20">
            
            <!-- Logo & Brand Title Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center p-2 bg-gradient-to-tr from-emerald-100 to-teal-50 rounded-2xl shadow-inner mb-3 border border-emerald-200/50 transform hover:scale-105 transition-transform duration-300">
                    <!-- Ganti path gambar sesuai letak file logo PANDU HD Anda -->
                    <img src="/logo.jpeg" alt="Logo PANDU HD" class="w-16 h-16 object-contain" />
                </div>
                <h2 class="text-2xl font-bold tracking-tight text-slate-800">
                    PANDU HD
                </h2>
                <p class="text-xs text-emerald-600 font-semibold uppercase tracking-wider mt-0.5">
                    RSUD Ngimbang
                </p>
                <p class="text-xs text-slate-500 mt-2">
                    Pelayanan Hemodialisa Terpadu
                </p>
            </div>

            <!-- Status Alert Message -->
            <div v-if="status" class="mb-6 p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-sm font-medium text-emerald-700 text-center animate-fade-in">
                {{ status }}
            </div>

            <!-- Form Start -->
            <form @submit.prevent="submit" class="space-y-5">
                
                <!-- Input Email / No RM -->
                <div>
                    <InputLabel for="login" class="text-slate-700 font-medium text-xs uppercase tracking-wider mb-1.5" value="Email (Petugas) / No. RM (Pasien)" />
                    <div class="relative rounded-xl shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <!-- User / ID Badge Icon -->
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <TextInput
                            id="login"
                            type="text"
                            class="pl-10 block w-full rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 text-sm transition-all duration-200 py-2.5 bg-slate-50/50 focus:bg-white"
                            v-model="form.login"
                            required
                            autofocus
                            placeholder="Contoh: RM-2026-001 atau email@rsud.id"
                        />
                    </div>
                    <InputError class="mt-1.5" :message="form.errors.login" />
                </div>

                <!-- Input Password (Animated Show/Hide Transition) -->
                <Transition
                    enter-active-class="transition duration-300 ease-out"
                    enter-from-class="transform -translate-y-2 opacity-0"
                    enter-to-class="transform translate-y-0 opacity-100"
                    leave-active-class="transition duration-200 ease-in"
                    leave-from-class="transform translate-y-0 opacity-100"
                    leave-to-class="transform -translate-y-2 opacity-0"
                >
                    <div v-if="isEmailInput">
                        <InputLabel for="password" class="text-slate-700 font-medium text-xs uppercase tracking-wider mb-1.5" value="Password Petugas" />
                        <div class="relative rounded-xl shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <!-- Lock Icon -->
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <TextInput
                                id="password"
                                type="password"
                                class="pl-10 block w-full rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 text-sm transition-all duration-200 py-2.5 bg-slate-50/50 focus:bg-white"
                                v-model="form.password"
                                :required="isEmailInput"
                                autocomplete="current-password"
                                placeholder="Masukkan password Anda"
                            />
                        </div>
                        <InputError class="mt-1.5" :message="form.errors.password" />
                    </div>
                </Transition>

                <!-- Remember Me & Forgot Password Options -->
                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center cursor-pointer group">
                        <Checkbox name="remember" v-model:checked="form.remember" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 transition-colors" />
                        <span class="ms-2 text-xs font-medium text-slate-600 group-hover:text-slate-800 transition-colors">Ingat Saya</span>
                    </label>

                    <Link
                        v-if="canResetPassword && isEmailInput"
                        :href="route('password.request')"
                        class="text-xs font-semibold text-emerald-600 hover:text-emerald-700 focus:outline-none focus:underline transition-colors"
                    >
                        Lupa Password?
                    </Link>
                </div>

                <!-- Submit Button with Spinner -->
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="w-full mt-2 py-3 px-4 rounded-xl text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 shadow-lg shadow-emerald-600/30 font-semibold text-sm transition-all duration-200 active:scale-[0.99] disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center space-x-2"
                >
                    <!-- Loading Spinner -->
                    <svg v-if="form.processing" class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    
                    <span>{{ form.processing ? 'Memproses...' : 'Masuk Aplikasi' }}</span>
                </button>
            </form>

            <!-- Footer Section -->
            <div class="mt-8 text-center border-t border-slate-100 pt-5">
                <p class="text-[11px] text-slate-400">
                    &copy; {{ new Date().getFullYear() }} RSUD Ngimbang. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</template>