<template>
    <div class="card p-3 w-full rounded-3xl" style="min-width: 800px;">
        <DataTable class="table display" :options="options" :columns="columns" :data="data" ref="table">
            <template #action="props">
                <div class="flex justify-center items-center">
                    <div data-bs-toggle="tooltip" data-bs-placement="bottom" title="Editar">
                        <Icon name="edit" @click="" class="pr-4" />
                    </div>
                    <Link method="delete" as="button"
                        :href="route('investigator.destroy', { 'userID': props.rowData.id })" data-bs-toggle="tooltip"
                        data-bs-placement="bottom" title="Eliminar">
                    <Icon name="delete" />
                    </Link>
                </div>
            </template>
        </DataTable>
    </div>
</template>


<script setup>
import { Link } from '@inertiajs/inertia-vue3';
import { DataTable } from 'datatables.net-vue3';
import Icon from '@/Shared/Icon.vue';
import { onMounted, onBeforeUpdate, ref } from 'vue';

const props = defineProps({
    users: { type: Array, required: true },
    isCollaborator: Boolean
})
let dt;
const data = ref([]);
//const table = ref();

onMounted(() => {
    //dt = table.value.dt;
    props.users.forEach((user) => addData(user))
})

onBeforeUpdate(() => {
    data.value = [];
    props.users.forEach((user) => addData(user));
})

const addData = (user) => {
    data.value.push({
        id: user.id,
        name: user.name,
        document: user.document,
        phone: user.phone,
        email: user.email,
    });
}

const options = {
    responsive: true,
    language: {
        "emptyTable": "No se encontraron resultados",
        "info": "Mostrando _START_ - _END_ de _TOTAL_ resultados",
        "infoEmpty": "Mostrando 0 - 0 de 0 registros",
        "infoFiltered": "(Filtrando de _MAX_ registros)",
        "lengthMenu": "Mostrar _MENU_ resultados",
        "loadingRecords": "Cargando...",
        "processing": "Cargando...",
        "search": "Buscar:",
        "zeroRecords": "No se encontraron coincidencias",
        "paginate": {
            "first": "Primero",
            "last": "Ultimo",
            "next": "Siguiente",
            "previous": "Anterior"
        },
    }
}

const columns = [
    {
        data: 'id',
        title: 'ID',
    },
    {
        data: 'name',
        title: 'Nombre',
    },
    {
        data: 'document',
        title: 'Documento',
    },
    {
        data: 'phone',
        title: 'Teléfono',
    },
    {
        data: 'email',
        title: 'Correo',
    },
    {
        data: null,
        title: 'Acciones',
        render: '#action',
    }
];
</script>