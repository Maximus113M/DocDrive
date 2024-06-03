<script setup>
import AuthLayout from '@/Layouts/Authenticated.vue';
import Table from '@/Shared/users/UsersTable.vue'
import CreateUserModal from '@/Shared/users/CreateUserModal.vue';
import { onUpdated } from 'vue';
defineProps({
    investigators: Object,
    isAuthenticated: Boolean,
    role: String,
    sucessDelete: Boolean,
})

onUpdated(() => {
    if (sucessDelete === true) {
        CustomAlertsService.generalAlert({
            title: 'Usuario Eliminado',
            text: 'El usuario ha sido eliminado correctamente',
            isToast: true,
            timer: 3000,
            showTimer: true,
        })
    }
})

</script>

<template>
    <AuthLayout :role="role" v-if="isAuthenticated">
        <div class="w-full m-auto px-4">
            <div style="min-height: 500px;">
                <Table :users="investigators" :isCollaborator="false" tableType="investigator" />
            </div>
        </div>
    </AuthLayout>

    <!-- Modal -->
    <CreateUserModal user-type='investigator' modal-id="modalSaveInvestigator" />

</template>