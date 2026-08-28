<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PasienDashboard from '@/Layouts/PasienDashboard.vue';
import PetugasDashboard from '@/Layouts/PetugasDashboard.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth.user);

// Menerima Props dari Laravel
const props = defineProps({
    pasien: Object,
    stats: Object,
    criticalPatients: Array,
    weeklyData: Array,
});

const isPasien = computed(() => user.value?.role === 'pasien' || !!user.value?.no_rm);
</script>

<template>
    <Head title="Dashboard - PANDU HD" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-bold text-xl text-slate-800 leading-tight">
                {{ isPasien ? 'Dashboard Pasien' : 'Dashboard Petugas & Tim Medis' }}
            </h2>
        </template>

        <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <PasienDashboard 
                v-if="isPasien" 
                :user="user" 
                :pasien="pasien" 
            />

            <PetugasDashboard 
                v-else 
                :user="user"
                :stats="stats"
                :critical-patients="criticalPatients"
                :weekly-data="weeklyData"
            />
        </div>
    </AuthenticatedLayout>
</template>