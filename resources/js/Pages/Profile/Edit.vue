<script setup>
import AuthLayout from '@/Layouts/Authenticated.vue';
import { CustomAlertsService } from '@/services/customAlerts';
import { useForm, usePage } from '@inertiajs/inertia-vue3';

const { user } = defineProps({
    user: Object
})

const validConfirmPassowrd = () => {
    return passwordForm.password == passwordForm.password_confirmation
        || !passwordForm.password || !passwordForm.password_confirmation
}

const passwordForm = useForm({
    oldPassword: null,
    password: null,
    password_confirmation: null
})

const form = useForm({
    name: user.name,
    email: user.email,
    document: user.document,
    phone: user.phone,
})


const role = user.role.name;

const updateUser = () => {
    form.submit("post", "/profile/update", {
        onSuccess: () => showMessage(),
        onError: (e) => {
            CustomAlertsService.generalAlert({
                title: 'Error',
                text: 'Ha ocurrido un error al actualizar tu perfil',
                icon: "error",
                isToast: true,
            })
        }
    })
}

const changePassword = () => {
    if (!validConfirmPassowrd()) {
        passwordForm.errors.password = "Las contraseñas no coiciden"
        passwordForm.errors.password_confirmation = "Las contraseñas no coiciden"
        return
    }
    passwordForm.submit("post", "/profile/password", {
        onSuccess: () => {
            const flashMessage = usePage().props.value.flash.message
            const errorMessage = usePage().props.value.flash.errorMessage
            console.log(errorMessage);
            if (errorMessage != null) {
                CustomAlertsService.generalAlert({
                    title: 'Error',
                    text: errorMessage,
                    icon: "error",
                    isToast: true,
                })
                return
            }
            CustomAlertsService.successConfirmAlert({
                title: flashMessage,
            })
            closeModal()
        },
        onError: (e) => {
            CustomAlertsService.generalAlert({
                title: 'Error',
                text: 'Ha ocurrido un error al cambiar la contraseña',
                icon: "error",
                isToast: true,
            })
        }
    })
}

const showMessage = () => {
    CustomAlertsService.successConfirmAlert({
        title: "Se ha actualizado correctamente tus datos",
    })
}

const closeModal = () => {
    const modal = document.getElementById("modalChangePassword")
    const modalBootstrap = bootstrap.Modal.getInstance(modal)
    modalBootstrap.hide()
}

</script>

<template>

    <AuthLayout :role="role">
        <div class="w-full md:w-10/12 m-auto px-4">
            <h4 class="pt-4 my-4" style="color: #39A900">
                <strong>Editar mi perfil </strong>
            </h4>
            <form class="sm:grid md:grid-cols-2" @submit.prevent="updateUser">
                <div class="mb-3">
                    <label for="name" class="font-bold">Nombre</label>
                    <input v-model="form.name" type="text" class="form-control" id="name">
                    <div class="text-red-400" v-if="form.errors.name">{{
                        form.errors.name }}</div>
                </div>
                <div class="mb-3">
                    <label for="email" class="font-bold">Email</label>
                    <input v-model="form.email" type="email" class="form-control" id="email">
                    <div class="text-red-400" v-if="form.errors.email">{{
                        form.errors.email }}</div>

                </div>
                <div class="mb-3">
                    <label for="document" class="font-bold">Documento</label>
                    <input v-model.number="form.document" type="number" class="form-control" id="document">
                    <div class="text-red-400" v-if="form.errors.document">{{
                        form.errors.document }}</div>
                </div>
                <div class="mb-3">
                    <label for="phone" class="font-bold">Teléfono</label>
                    <input v-model.number="form.phone" type="number" class="form-control" id="phone">
                    <div class="text-red-400" v-if="form.errors.phone">{{
                        form.errors.phone }}</div>
                </div>
                <div class="col-span-2 w-full sm:w-2/5 row justify-center p-3">
                    <button :disabled="form.processing" type="submit" class="btn py-2 text-white"
                        :style="`background-color: #39A900`">
                        <strong>Actualizar</strong>
                    </button>
                </div>
                <div class="col-span-2 w-full sm:w-2/5 row justify-center p-3 pt-0">
                    <button data-bs-toggle="modal" data-bs-target="#modalChangePassword" type="button"
                        class="btn py-2 text-white" :style="`background-color: #FF6624`">
                        <strong>Cambiar contraseña</strong>
                    </button>
                </div>


            </form>
        </div>

        <div class="modal fade" id="modalChangePassword" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" style="width: 350px; height: 600px;">
                <div class="modal-content position-relative p-3" style="max-height: 400px;">
                    <div class="d-flex flex-row justify-center px-3">
                        <h4 class="my-3" style="color: #FF6624;"><strong>Cambiar contraseña</strong></h4>

                        <button type="button" class="btn-close position-absolute top-1 end-3" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body px-3">
                        <form @submit.prevent="changePassword">
                            <div class="mb-3">
                                <label for="oldPassword" class="font-bold">Contraseña</label>
                                <input v-model="passwordForm.oldPassword" type="password" class="form-control"
                                    id="oldPassword">
                                <div class="text-red-400" v-if="passwordForm.errors.oldPassword">{{
                                    passwordForm.errors.oldPassword }}</div>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="font-bold">Contraseña nueva</label>
                                <input v-model="passwordForm.password" type="password" class="form-control"
                                    id="password">
                                <div class="text-red-400" v-if="passwordForm.errors.password">{{
                                    passwordForm.errors.password }}</div>
                            </div>
                            <div class="mb-3">
                                <label for="password_confirmation" class="font-bold">Confirmar Contraseña</label>
                                <input v-model="passwordForm.password_confirmation" type="password" class="form-control"
                                    id="password_confirmation">
                                <div class="text-red-400" v-if="passwordForm.errors.password_confirmation">{{
                                    passwordForm.errors.password_confirmation }}</div>
                            </div>
                            <div class="row justify-center p-3 my-3">
                                <button type="submit" class="btn py-2"
                                    style="background-color: #FF6624; color: white; "><strong>Cambiar</strong></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </AuthLayout>


</template>
