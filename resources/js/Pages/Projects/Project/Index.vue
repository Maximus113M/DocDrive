<template>

    <AuthLayout :role="role">
        <ProjectContent :folder="folder" :collaborators="collaborators" :investigators="investigators" :current-year="currentYear" 
        :project="project" :visualizations-role="visualizationsRole" :document-categories="documentCategories" />
    </AuthLayout>

</template>

<script setup>
import AuthLayout from '@/Layouts/Authenticated.vue';
import ProjectContent from '@/Pages/Projects/Project/Components/ProjectContent.vue'
import { usePage } from '@inertiajs/inertia-vue3';


const {  project } = defineProps({
    folder: Object,
    project: Object,
    visualizationsRole: {type: Array, required: true},
    currentYear: {type: String, required: true},
    investigators: {type: Array, required: true},
    collaborators: { type: Array, required: true },
    documentCategories: { type: Array, required: true },
})

const authUser = usePage().props.value.auth.user ?? null;
const role = authUser ? authUser.role.name : 'guest';

</script>
