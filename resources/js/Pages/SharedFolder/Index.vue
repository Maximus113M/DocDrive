<template>

    <AuthLayout :role="role">
        <SharedFolderContent :folder="folder" :childrenFolders="childrenFolders" />
    </AuthLayout>

</template>

<script setup>
import AuthLayout from '@/Layouts/Authenticated.vue';
import SharedFolderContent from '@/Pages/SharedFolder/Components/SharedFolderContent.vue'
import { usePage } from '@inertiajs/inertia-vue3';


defineProps({
    folder: Object,
    childrenFolders: Object,
})


const authUser = usePage().props.value.auth.user ?? null;
const role = authUser ? authUser.role.name : 'guest';


</script>
