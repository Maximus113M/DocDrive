<template>
    <div ref="modal" class="modal fade" :id="modalId" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" style="width: 400px;">
            <div class="modal-content position-relative p-3" style="max-height: 800px;">
                <div class="d-flex flex-row justify-center px-3">
                    <h4 class="my-3" style="color: #39A900;"><strong>{{
                        props.userType === 'investigator' ? 'Nuevo Investigador' : 'Nuevo Colaborador' }} </strong>
                    </h4>

                    <button type="button" class="btn-close position-absolute top-2 end-2" aria-label="Close"
                        @click="closeModal"></button>
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
                            <label for="document" class="font-bold">Documento</label>
                            <input v-model.number="form.document" type="number" class="form-control" id="document">
                            <div v-if="form.errors.document">{{ form.errors.document }}</div>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="font-bold">Teléfono</label>
                            <input v-model.number="form.phone" type="number" class="form-control" id="phone">
                            <div v-if="form.errors.phone">{{ form.errors.phone }}</div>
                        </div>
                        <div class="row justify-center p-3 mt-5">
                            <button :disabled="form.processing" type="submit" class="btn py-2"
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
import { ref } from "vue";
import { useForm } from '@inertiajs/inertia-vue3';
import { CustomAlertsService } from "@/services/customAlerts";

const props = defineProps({
    userType: { type: String, required: true },
    modalId: { type: String, required: true }
});

const modal = ref(null);

const form = useForm({
    name: null,
    email: null,
    document: null,
    phone: null,
});

const alertService = new CustomAlertsService();

const saveUser = () => {
    form.transform((data) => ({
        ...data,
        document: data.document ? `${data.document}` : null,
        phone: data.phone ? `${data.phone}` : null,
    })).post(route(props.userType === 'investigator' ? 'investigator.store' : 'collaborator.store'), {
        onSuccess: () => {
            closeModal();

            alertService.successConfirmAlert({
                title: props.userType === 'investigator' ? 'Investigar Creado' : 'Colaborador Creado',
                text: "Se ha enviado la contraseña al email registrado.",
            })
        },
        onError: ()=>{
            alertService.errorAlert({
                title: 'Error',
                text: `Ha ocurrido un error al crear al ${props.userType === 'investigator' ? 'Investigador' : 'Colaborador'}`,
            })
        }
    })
}

const closeModal = () => {
    form.clearErrors();
    form.reset();
    const modalBootstrap = bootstrap.Modal.getInstance(modal.value)
    modalBootstrap.hide();
}

</script>