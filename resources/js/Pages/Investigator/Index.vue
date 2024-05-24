<script setup>
import AuthLayout from '@/Layouts/Authenticated.vue';
import { useForm } from '@inertiajs/inertia-vue3'
import Table from '@/Components/UsersTable.vue'
import { ref } from 'vue';


const modal = ref(null)



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
    form.post(route('investigator.store'), {
        onSuccess: () => closeModal()
    })
}


const closeModal = () => {
    const modalBootstrap = bootstrap.Modal.getInstance(modal.value)
    modalBootstrap.hide()
}


</script>

<template>


    <AuthLayout :role="role" v-if="isAuthenticated">

        <div class="w-10/12 py-10 float-right m-auto relative overflow-x-auto sm:rounded-lg">

            <div class="m-auto w-9/12">
                <button type="button" class="my-4 btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#modalSaveInvestigator">
                    Crear Investigador
                </button>
                <h3 v-if="investigators < 1">¡No hay investigadores creados!</h3>

                <Table v-else :users="investigators" :isCollaborator="false" />
            </div>
        </div>

    </AuthLayout>


    <!-- Modal -->

    <div ref="modal" class="modal fade" id="modalSaveInvestigator" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
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