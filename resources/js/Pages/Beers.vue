<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    defineProps(['beers'])
</script>

<template>
    <AuthenticatedLayout>
        <div class="py-12">
            <template v-for="beer in beers" :keys="beer.id">
                <span class="text-blue-700">{{ beer.name }}</span>
            </template>
        </div>
    </AuthenticatedLayout>
</template>
