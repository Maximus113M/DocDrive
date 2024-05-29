<script setup>
import { Link } from '@inertiajs/inertia-vue3';
import { DataTable } from 'datatables.net-vue3';
import Icon from '@/Shared/Icon.vue';


defineProps({
    users: Object,
    isCollaborator: Boolean
})

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
</script>

<template>
    <div class="card p-3 w-full rounded-3xl">
        <DataTable class="table" :options="options">
            <thead>
                <tr>
                    <th class="text-center">ID</th>
                    <th class="text-center">Nombre</th>
                    <th class="text-center">Documento</th>
                    <th class="text-center">Telefono</th>
                    <th class="text-center">Email</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- TODO VALIDAR SI SON NECESARIOS MAS CAMPOS, EXAMPLE -->
                <tr v-for="user in users">
                    <td class="text-center">{{ user.id }}</td>
                    <td class="text-center">{{ user.name }}</td>
                    <td class="text-center"> 1089562458 </td>
                    <td class="text-center"> 3156262456 </td>
                    <td class="text-center">{{ user.email }}</td>
                    <td class="flex justify-center items-center">

                        <!-- TODO Configurar modal de edicion -->
                        <div data-bs-toggle="tooltip" data-bs-placement="bottom" title="Editar">
                            <Icon name="edit" @click="" class="pr-4" />
                        </div>
                        <!-- TODO Debe salir un dialogo de confirmacion -->
                        <Link method="delete" :href="route('investigator.destroy', { 'userID': user.id })"
                            data-bs-toggle="tooltip" data-bs-placement="bottom" title="Eliminar">
                        <Icon name="delete" />
                        </Link>

                    </td>
                </tr>
            </tbody>
        </DataTable>
    </div>
</template>