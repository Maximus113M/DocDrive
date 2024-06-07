<script setup>
import AuthLayout from '@/Layouts/Authenticated.vue';


const { user } = defineProps({
    user:Object
})
console.log(user);
const role = user.role.name;

</script>

<template>

    <AuthLayout :role="role">
        
    </AuthLayout>


</template>

