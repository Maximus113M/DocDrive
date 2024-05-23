<script setup>
import AuthLayout from '@/Layouts/Authenticated.vue';


defineProps({
    role:String,
    prjects:Object,
    isAuthenticated:Boolean
})


</script>

<template>


    <AuthLayout :role="role">
        <h1>PROJECTS</h1>
    </AuthLayout>


</template>

