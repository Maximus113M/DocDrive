<template>

    <AuthLayout :role="role">
        <ProjectContent :current-year="currentYear" :project="project"/>
    </AuthLayout>

</template>

<script setup>
import AuthLayout from '@/Layouts/Authenticated.vue';
import ProjectContent from '@/Pages/Projects/Project/Components/ProjectContent.vue'
import { usePage } from '@inertiajs/inertia-vue3';


defineProps({
    project: Object,
    currentYear: {type: String, required: true},
})

const authUser = usePage().props.value.auth.user ?? null;
const role = authUser ? authUser.role.name : 'guest';

</script>
