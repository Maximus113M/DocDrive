<script setup>
import AuthLayout from '@/Layouts/Authenticated.vue';
import CreateUserModal from '@/Shared/users/CreateUserModal.vue';
import Table from '@/Shared/users/UsersTable.vue'
import Icon from '@/Shared/Icon.vue';

defineProps({
    collaborators: Object,
    isAuthenticated: Boolean,
    role: String,
})

</script>

<template>
    <AuthLayout :role="role" v-if="isAuthenticated">

        <div class="w-full m-auto px-4">
            <Table :users="collaborators" :isCollaborator="true" usersType="collaborator"/>
        </div>

    </AuthLayout>


    <!-- Modal -->
    <CreateUserModal user-type='collaborator' modal-id="modalSaveCollaborator"/>

</template>