<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
});

const classes = computed(() =>
    props.active
        ? 'w-full flex items-center gap-3 px-3.5 py-3 text-sm font-semibold rounded-xl bg-emerald-600 text-white shadow-lg shadow-emerald-950/50 transition-all duration-200 relative group'
        : 'w-full flex items-center gap-3 px-3.5 py-3 text-sm font-medium rounded-xl text-emerald-100/70 hover:bg-emerald-900/50 hover:text-white transition-all duration-200 group'
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>