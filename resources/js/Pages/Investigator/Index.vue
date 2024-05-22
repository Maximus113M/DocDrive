<script setup>
import AuthLayout from '@/Layouts/Authenticated.vue';
import { Link, useForm } from '@inertiajs/inertia-vue3'
import { ref } from 'vue';

const form = useForm({
    name: null,
    email: null,
    password: null,
})

defineProps({
    investigators: Object,
    isAuthenticated: Boolean,
    role: String,
})

const saveInvestigator = () => {
    form.post(route('investigator.store'))
}


</script>

<template>


    <AuthLayout :role="role" v-if="isAuthenticated">
        <div class='flex justify-center'>
            <div class="flex items-center justify-center min-h-[450px]">
                <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
                    <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
                        <button data-bs-toggle="modal"
                            data-bs-target="#modalSaveInvestigator"
                            class="rounded px-3 py-2 m-1 border-b-4 border-l-2 shadow-lg bg-green-800 border-green-900 text-white">
                            Crear investigador
                        </button>
                        <h3 v-if="investigators < 1">¡No hay investigadores creados!</h3>
                        <table v-else class="w-full text-sm text-left text-black dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="py-3 px-6">ID</th>
                                    <th scope="col" class="py-3 px-6">Nombre</th>
                                    <th scope="col" class="py-3 px-6">Email</th>
                                    <th scope="col" class="py-3 px-6">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="i in investigators" class="bg-white dark:bg-gray-800">
                                    <td class="py-4 px-6">{{i.id}}</td>
                                    <td class="py-4 px-6">{{i.name}}</td>
                                    <td class="py-4 px-6">{{i.email}}</td>
                                    <td class="py-4 px-6">
                                        <Link method="delete" :href="route('investigator.destroy', {'userID' : i.id})">
                                            <button type="button" class="btn btn-danger">Eliminar</button>
                                        </Link>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </AuthLayout>

    
    <!-- Modal -->

    <div class="modal fade" id="modalSaveInvestigator" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Crear nuevo investigador</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="saveInvestigator">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre</label>
                            <input v-model="form.name" type="text" class="form-control" id="name">
                            <div v-if="form.errors.name">{{ form.errors.name }}</div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input v-model="form.email" type="email" class="form-control" id="email">
                            <div v-if="form.errors.email">{{ form.errors.email }}</div>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input v-model="form.password" type="password" class="form-control" id="password">
                            <div v-if="form.errors.password">{{ form.errors.password }}</div>
                        </div>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="ml-4 btn btn-primary">Crear investigador</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</template>