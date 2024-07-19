<script setup>
import AuthLayout from '@/Layouts/Authenticated.vue';
import Table from '@/Shared/users/UsersTable.vue'
import CreateUserModal from '@/Shared/users/CreateUserModal.vue';
const props= defineProps({
    investigators: Object,
    isAuthenticated: Boolean,
    role: String,
})

</script>

<template>
    <AuthLayout :role="props.role" v-if="props.isAuthenticated">
        <div class="w-full m-auto px-4">
            <div style="min-height: 500px;">
                <Table :users="props.investigators" :isCollaborator="false" usersType="investigator" />
            </div>
        </div>
    </AuthLayout>

    <!-- Modal -->
    <CreateUserModal user-type='investigator' modal-id="modalSaveInvestigator" />

</template>