<script setup>
import AuthLayout from '@/Layouts/Authenticated.vue';
import { usePage } from '@inertiajs/inertia-vue3';


defineProps({
    documents : Object,
    folders : Object,
})

const authUser = usePage().props.value.auth.user ?? null;
const role = authUser ? authUser.role.name : 'guest';

</script>

<template>
    
    <AuthLayout :role="role">
    </AuthLayout>


</template>

