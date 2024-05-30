<script setup>
import AuthLayout from '@/Layouts/Authenticated.vue';
import ContentIndex from './Components/ContentIndexProject.vue';


defineProps({
    role:String,
    projects:Object,
    validityID:Number,
    isAuthenticated:Boolean,
    investigators:Object,
    visualizationsRole:Object
})


</script>

<template>
    
    <AuthLayout :role="role">
        <ContentIndex :visualizationsRole="visualizationsRole" :validityID="validityID" :investigators="investigators" :projects="projects" :isAuthenticated="isAuthenticated" :role="role" />   
    </AuthLayout>


</template>

