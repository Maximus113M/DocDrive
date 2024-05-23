<script setup>
import { Link } from '@inertiajs/inertia-vue3';


defineProps({
    users:Object,
    isCollaborator:Boolean
})

</script>

<template>
    <table class="w-full text-sm text-left rtl:text-right text-black dark:text-gray-400">
        <thead class="text-xs text-white uppercase bg-gray-50 bg-lime-600">
            <tr>
                <th scope="col" class="px-6 py-3">
                    ID
                </th>
                <th scope="col" class="px-6 py-3">
                    <div class="flex items-center">
                        Nombre
                    </div>
                </th>
                <th scope="col" class="px-6 py-3">
                    <div class="flex items-center">
                        Email
                    </div>
                </th>
                <th scope="col" class="px-6 py-3">
                    <div class="flex items-center">
                        Acciones
                    </div>
                </th>

            </tr>
        </thead>
        <tbody>
            <tr v-for="u in users" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                <td scope="row" class="px-6 py-4 font-medium text-black whitespace-nowrap dark:text-white">
                    {{ u.id }}</td>
                <td class="px-6 py-4">{{ u.name }}</td>
                <td class="px-6 py-4">{{ u.email }}</td>
                <td class="px-6 py-4">
                    <Link v-if="!isCollaborator" method="delete" :href="route('investigator.destroy', { 'userID': u.id })">
                        <button type="button" class="btn btn-danger">Eliminar</button>
                    </Link>
                    <Link v-else method="delete" :href="route('collaborator.destroy', { 'userID': u.id })">
                        <button type="button" class="btn btn-danger">Eliminar</button>
                    </Link>
                </td>
            </tr>
        </tbody>
    </table>
</template>
