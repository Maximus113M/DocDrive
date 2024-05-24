<script setup>
import AuthLayout from '@/Layouts/Authenticated.vue';
import ContentIndex from './Components/ContentIndexProject.vue';
import GuestLayout from '@/Layouts/Guest.vue';


defineProps({
    role:String,
    projects:Object,
    isAuthenticated:Boolean
})


</script>

<template>


    <AuthLayout v-if="isAuthenticated" :role="role">
        <ContentIndex :projects="projects" :isAuthenticated="isAuthenticated" :role="role" />   
    </AuthLayout>


    <GuestLayout v-else :role="role">
        <ContentIndex :projects="projects" :isAuthenticated="isAuthenticated" :role="role" />   
    </GuestLayout>



</template>

