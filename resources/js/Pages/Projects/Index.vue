<script setup>
import AuthLayout from '@/Layouts/Authenticated.vue';
import ContentIndex from './Components/ContentIndexProjects.vue';


defineProps({
    role:String,
    projects:Object,
    validityID:Number,
    investigators:Object,
    visualizationsRole:Object,
    currentYear: String,
})

</script>

<template>
    
    <AuthLayout :role="role">
        <ContentIndex :visualizationsRole="visualizationsRole" :validityID="validityID" :investigators="investigators" :projects="projects" :role="role" :currentYear="currentYear"/>   
    </AuthLayout>

</template>

