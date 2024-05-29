<script setup>
import AuthLayout from '@/Layouts/Authenticated.vue';
import CreateUserModal from '@/Shared/CreateUserModal.vue';
import Table from '@/Shared/UsersTable.vue'
import Icon from '@/Shared/Icon.vue';

defineProps({
    collaborators: Object,
    isAuthenticated: Boolean,
    role: String,
})

</script>

<template>
    <AuthLayout :role="role" v-if="isAuthenticated">

        <div class="w-full m-auto relative overflow-y-auto px-4">
            <div>
                <button type="button" class="flex items-center justify-center my-4 px-3 rounded-3xl"
                    style="background-color: #FF6624; color: white;" data-bs-toggle="modal"
                    data-bs-target="#modalSaveCollaborator">
                    <Icon name="add" class="w-7 pr-1" />
                    Colaborador
                </button>
            </div>

            <Table :users="collaborators" :isCollaborator="true" />
        </div>

    </AuthLayout>


    <!-- Modal -->
    <CreateUserModal user-type='collaborator' modal-id="modalSaveCollaborator"/>

</template>