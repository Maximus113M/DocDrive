<script setup>
import { Link } from '@inertiajs/inertia-vue3';

defineProps(['name', 'id']);


</script>

<template>

    <div class="card">
        <div class="card-body">
            <h6 class="card-title">{{ name }}</h6>
            <!--<Link :href="route('validity.projects', {'validityYear' : year})" class="btn btn-primary">Ver más</Link>-->
        </div>
    </div>
</template>