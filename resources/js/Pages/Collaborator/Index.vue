<script setup>
import AuthLayout from '@/Layouts/Authenticated.vue';
import { useForm } from '@inertiajs/inertia-vue3'
import Table from '@/Shared/UsersTable.vue'
import { ref } from 'vue';
import Icon from '@/Shared/Icon.vue';

const modal = ref(null)

const form = useForm({
    name: null,
    email: null,
    password: null,
})

defineProps({
    collaborators: Object,
    isAuthenticated: Boolean,
    role: String,
})

const saveCollaborator = () => {
    form.post(route('collaborator.store'), {
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

        <div class="w-full m-auto relative overflow-y-auto px-4">

            <div>
                <button type="button" class="flex items-center justify-center my-4 px-3 rounded-3xl"
                    style="background-color: #FF6624; color: white;" data-bs-toggle="modal"
                    data-bs-target="#modalSaveCollaborator">
                    <Icon name="add" class="w-7 pr-1" />
                    Colaborador
                </button>
            </div>

            <Table :users="collaborators" :isCollaborator="true" />
        </div>


    </AuthLayout>


    <!-- Modal -->

    <div ref="modal" class="modal fade" id="modalSaveCollaborator" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Crear nuevo colaborador</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="saveCollaborator">
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
                        <button type="submit" class="ml-4 btn btn-primary">Crear colaborador</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</template>