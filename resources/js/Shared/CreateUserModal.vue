<template>
    <div ref="modal" class="modal fade" :id="modalId" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" style="width: 400px; height: 1000px;">
            <div class="modal-content position-relative p-3" style="max-height: 800px;">
                <div class="d-flex flex-row justify-center px-3">
                    <h4 class="my-3" style="color: #39A900;"><strong>{{
                        props.userType === 'investigator' ? 'Nuevo Investigador' : 'Nuevo Colaborador' }} </strong>
                    </h4>

                    <button type="button" class="btn-close position-absolute top-1 end-3" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body px-3">
                    <form @submit.prevent="saveUser">
                        <div class="mb-3">
                            <label for="name" class="font-bold">Nombre</label>
                            <input v-model="form.name" type="text" class="form-control" id="name">
                            <div v-if="form.errors.name">{{ form.errors.name }}</div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="font-bold">Email</label>
                            <input v-model="form.email" type="email" class="form-control" id="email">
                            <div v-if="form.errors.email">{{ form.errors.email }}</div>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="font-bold">Contraseña</label>
                            <input v-model="form.password" type="password" class="form-control" id="password">
                            <div v-if="form.errors.password">{{ form.errors.password }}</div>
                        </div>
                        <div class="row justify-center p-3 mt-5">
                            <button type="submit" class=" py-2"
                                style="background-color: #39A900; color: white; "><strong>Crear
                                </strong></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onBeforeUpdate } from "vue";
import { useForm } from '@inertiajs/inertia-vue3'

const props = defineProps({
    userType: { type: String, required: true },
    modalId: { type: String, required: true }
})

const modal = ref(null)

const form = useForm({
    name: null,
    email: null,
    password: null,
})

const saveUser = () => {
    if (props.userType === 'investigator') {
        form.post(route('investigator.store'), {
            onSuccess: () => closeModal()
        })
    } else {
        form.post(route('collaborator.store'), {
            onSuccess: () => closeModal()
        })
    }
}

const closeModal = () => {
    form.name = null;
    form.email = null;
    form.password = null;
    form.clearErrors()
    form.reset()
    const modalBootstrap = bootstrap.Modal.getInstance(modal.value)
    modalBootstrap.hide()
}

</script>