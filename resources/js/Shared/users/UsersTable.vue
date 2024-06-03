<template>
    <div class="w-full">
        <div class="flex justify-between items-center">
            <button type="button" class="flex items-center justify-center my-4 px-3 pr-4 rounded-3xl"
                style="background-color: #39A900; color: white;" data-bs-toggle="modal"
                :data-bs-target="tableType === 'investigator' ? '#modalSaveInvestigator' : '#modalSaveCollaborator'">
                <Icon name="add" class="w-6 pr-1" />
                Investigador
            </button>

            <div class="flex pl-1 pr-3 py-2 rounded-2xl border-1 border-gray-300">
                <Icon name="search" />
                <input type="text" v-model="searchValue" style="all: unset" placeholder="Buscar">
            </div>
        </div>
    </div>

    <div class="card p-3 rounded-3xl overflow-y-auto">
        <Vue3EasyDataTable :headers="headers" :items="users" :search-field="searchFields" :search-value="searchValue"
            :rows-items="[10, 15, 25, 50]" :rows-per-page="10" rows-per-page-message="Filas por página"
            rows-of-page-separator-message="de" :theme-color="'#39A900'" buttons-pagination>

            <template #item-actions="item">
                <div class="flex justify-start items-center">

                    <Icon name="edit" @click="" class="pr-4" data-bs-toggle="tooltip" data-bs-placement="bottom"
                        title="Editar" />

                    <Icon name="delete" @click="showDeleteConfirm(item.id)" data-bs-toggle="tooltip"
                        data-bs-placement="bottom" title="Eliminar" />

                </div>
            </template>
        </Vue3EasyDataTable>
    </div>


</template>


<script setup>
import Vue3EasyDataTable from 'vue3-easy-data-table';
import 'vue3-easy-data-table/dist/style.css';
import { Link } from '@inertiajs/inertia-vue3';
import { useRouter } from '@inertiajs/vue3'
import Icon from '@/Shared/Icon.vue';
import { ref } from 'vue';
import { CustomAlertsService } from '@/services/customAlerts';

const props = defineProps({
    users: { type: Array, required: true },
    isCollaborator: Boolean,
    tableType: { type: String, required: true },
})
//const useRouter= new Link;
const searchValue = ref('')
const searchFields = ["id", "name", "document", "phone", "email"];

const showDeleteConfirm = (userId) => {
    CustomAlertsService.deleteConfirmAlert({
        title: 'Eliminar usuario',
        text: '¿Deseas eliminar al usuario seleccionado? Esta acción no se puede revertir'
    }).then((result) => {
        if (result.isConfirmed) {
            router.$inertia.delete(`/investigator/${userId}/destroy`)
            //route('investigator.destroy', { 'userID': userId })        
        }
    }
    )
     //<Link method="delete" as="button" :href="route('investigator.destroy', { 'userID': item.id })"/>
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