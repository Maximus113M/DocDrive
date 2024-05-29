<script setup>
import AuthLayout from '@/Layouts/Authenticated.vue';
import { useForm } from '@inertiajs/inertia-vue3'
import Table from '@/Shared/UsersTable.vue'
import { ref } from 'vue';
import Icon from '@/Shared/Icon.vue';
import CreateUserModal from '@/Shared/CreateUserModal.vue';

defineProps({
    investigators: Object,
    isAuthenticated: Boolean,
    role: String,
})

</script>

<template>


    <AuthLayout :role="role" v-if="isAuthenticated">
        <div class="w-full m-auto relative overflow-y-auto px-4">
            <div>
                <button type="button" class="flex items-center justify-center my-4 px-3 rounded-3xl"
                    style="background-color: #39A900; color: white;" data-bs-toggle="modal"
                    data-bs-target="#modalSaveInvestigator">
                    <Icon name="add" class="w-7 pr-1" />
                    Investigador
                </button>
            </div>

            <Table :users="investigators" :isCollaborator="false" />
        </div>
    </AuthLayout>


    <!-- Modal -->
    <CreateUserModal user-type='investigator' modal-id="modalSaveInvestigator"/>

</template>