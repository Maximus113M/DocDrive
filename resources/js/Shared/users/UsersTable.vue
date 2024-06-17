<template>
    <div class="w-full">
        <div class="flex justify-between items-center">
            <button type="button" class="flex items-center justify-center my-4 px-3 pr-4 rounded-3xl text-white"
                :style="`background-color: ${tableTheme}`" data-bs-toggle="modal"
                :data-bs-target="usersType === 'investigator' ? '#modalSaveInvestigator' : '#modalSaveCollaborator'">
                <Icon name="add" class="w-6 pr-1" />
                {{ usersType === 'investigator' ? 'Investigador' : 'Colaborador' }}
            </button>

            <div class="flex pl-1 pr-3 py-2 rounded-2xl border-1 border-gray-300">
                <Icon name="search" />
                <input type="text" v-model="searchValue" style="all: unset" placeholder="Buscar">
            </div>
        </div>
    </div>

    <div class="card p-3 rounded-3xl">
        <Vue3EasyDataTable :headers="headers" :items="users" :search-field="searchFields" :search-value="searchValue"
            :rows-items="[10, 15, 25, 50]" :rows-per-page="10" rows-per-page-message="Filas por página"
            empty-message="No se encontraron resultados" rows-of-page-separator-message="de" :theme-color="tableTheme"
            buttons-pagination>

            <template #item-actions="item">
                <div class="flex justify-start items-center">
                    <!-- data-bs-toggle="tooltip" data-bs-placement="bottom" title="Editar" -->
                    <div>
                        <Icon name="edit" @click="selectedUserToEdit(item)" class="pr-4" data-bs-toggle="modal"
                            :data-bs-target="`#${usersType}`" />
                    </div>
                    <div data-bs-toggle="tooltip" data-bs-placement="bottom" title="Eliminar">
                        <Icon name="delete" @click="showDeleteConfirm(item.id)" />
                    </div>
                </div>
            </template>
        </Vue3EasyDataTable>
    </div>

    <EditUserModal :user-type=usersType :modal-id=usersType :current-user="currentUser" :is-showing="isShowingModal"
        @loaded-modal="changeVisibilityModalStatus" />
</template>


<script setup>
import EditUserModal from './EditUserModal.vue';
import Vue3EasyDataTable from 'vue3-easy-data-table';
import 'vue3-easy-data-table/dist/style.css';
import { useForm } from '@inertiajs/inertia-vue3';
import Icon from '@/Shared/Icon.vue';
import { ref } from 'vue';
import { CustomAlertsService } from '@/services/customAlerts';
import { UserModel } from '@/models/userModel';

const props = defineProps({
    users: { type: Array, required: true },
    isCollaborator: Boolean,
    usersType: { type: String, required: true },
})
const tableTheme = props.usersType === 'investigator' ? '#39A900' : '#FF6624'
const searchValue = ref('')
const searchFields = ["id", "name", "document", "phone", "email"];

const currentUser = ref(undefined);
const isShowingModal = ref(false);

const changeVisibilityModalStatus = (value) => {
    isShowingModal.value = !value;
}

const selectedUserToEdit = (user) => {
    isShowingModal.value = true;
    currentUser.value = new UserModel({ ...user });
}

const showDeleteConfirm = (userId) => {
    CustomAlertsService.deleteConfirmAlert({
        title: 'Eliminar usuario',
        text: '¿Deseas eliminar al usuario seleccionado? Esta acción no se puede revertir'
    }).then((result) => {
        if (result.isConfirmed) {
            useForm().delete(route(props.usersType === 'investigator' ? 'investigator.destroy' : 'collaborator.destroy', { 'userID': userId }), {
                onSuccess: () => {
                    CustomAlertsService.generalAlert({
                        title: props.usersType === 'investigator' ? 'Investigador Eliminado' : 'Colaborador Eliminado',
                        text: `El ${props.usersType === 'investigator' ? 'investigador' : 'colaborador'} ha sido eliminado exitosamente`,
                        isToast: true,
                        showTimer: true,
                        position: "top-end"
                    })
                },
                onError: () => {
                    CustomAlertsService.generalAlert({
                        title: 'Error',
                        text: `Ha ocurrido un error al eliminar al ${props.usersType === 'investigator' ? 'investigador' : 'colaborador'}`,
                        icon: "error",
                        isToast: true,
                    })
                }
            })
            //router.delete(`/investigator/${userId}/destroy`);
        }
    }
    )
}

const headers = [
    { text: "ID", value: "id", sortable: true },
    { text: "NOMBRE", value: "name", sortable: true },
    { text: "DOCUMENTO", value: "document", sortable: true },
    { text: "TELÉFONO", value: "phone" },
    { text: "CORREO", value: "email", sortable: true },
    { text: "ACCIONES", value: "actions" },
];

</script>