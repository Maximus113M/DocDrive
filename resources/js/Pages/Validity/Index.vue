<script setup>
import AuthLayout from '@/Layouts/Authenticated.vue';
import GuestLayout from '@/Layouts/Guest.vue';
import ContentIndex from '@/Pages/Validity/Components/ContentIndex.vue';


defineProps({
    validities: Object,
    isAuthenticated: Boolean,
    role: String,
})


</script>

<template>


    <AuthLayout :role="role" v-if="isAuthenticated">
        <ContentIndex :message="message" :isAuthenticated="isAuthenticated" :validities="validities" :role="role" #content/> 
    </AuthLayout>


    <GuestLayout v-if="!isAuthenticated">
        <ContentIndex :message="message" :isAuthenticated="isAuthenticated" :validities="validities" :role="role"/> 
    </GuestLayout>

</template>

