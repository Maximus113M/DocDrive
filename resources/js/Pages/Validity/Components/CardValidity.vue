<script setup>
import { Link } from '@inertiajs/inertia-vue3';

defineProps(['year', 'id']);


</script>

<template>

    <div class="card w-75">
        <div class="card-body">
            <h5 class="card-title">Vigencia {{ year }}</h5>
            <Link :href="route('validity.projects', {'validityYear' : year})" class="btn btn-primary">Ver más</Link>
        </div>
    </div>
</template>
