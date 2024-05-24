<script setup>
import AuthLayout from '@/Layouts/Authenticated.vue';
import ContentIndex from './Components/ContentIndexProject.vue';


defineProps({
    role:String,
    projects:Object,
    isAuthenticated:Boolean
})


</script>

<template>


    <AuthLayout :role="role">
        <ContentIndex :projects="projects" :isAuthenticated="isAuthenticated" :role="role" />   
    </AuthLayout>


</template>

