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
        ? 'block w-full px-4 py-3 text-base font-semibold rounded-xl bg-emerald-600 text-white shadow-md transition-all duration-200'
        : 'block w-full px-4 py-3 text-base font-medium rounded-xl text-emerald-100/80 hover:bg-emerald-900 hover:text-white transition-all duration-200'
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>