<script setup>
import AuthLayout from '@/Layouts/Authenticated.vue';
import ContentIndex from '@/Pages/Validity/Components/ContentIndex.vue';


defineProps({
    validities: Object,
    isAuthenticated: Boolean,
    role: String,
    sharedFolders: Array
})


</script>

<template>
    
    <AuthLayout :role="role">
        <ContentIndex  :sharedFolders="sharedFolders" :isAuthenticated="isAuthenticated" :validities="validities" :role="role" #content/> 
    </AuthLayout>

</template>

