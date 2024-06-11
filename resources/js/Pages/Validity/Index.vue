<script setup>
import AuthLayout from '@/Layouts/Authenticated.vue';
import ContentIndex from '@/Pages/Validity/Components/ContentIndex.vue';


defineProps({
    validities: Object,
    isAuthenticated: Boolean,
    role: String,
})


</script>

<template>
    
    <AuthLayout :role="role">
        <ContentIndex :isAuthenticated="isAuthenticated" :validities="validities" :role="role" #content/> 
    </AuthLayout>

</template>

