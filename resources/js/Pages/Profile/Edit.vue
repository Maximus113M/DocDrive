<script setup>
import AuthLayout from '@/Layouts/Authenticated.vue';
import { AppFunctions } from '@/core/appFunctions';
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

//TODO: verificar que se envie numericos los campos de CC y telefono
const updateUser = () => {
    form.submit("post", "/profile/update", {
        onSuccess: () => showMessage(),
        onError: (e) => {
            console.log(e);
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
            console.log(e);
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
    CustomAlertsService.generalAlert({
        title: "Se ha actualizado correctamente tus datos",
        isToast: true
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
        <div class="border-2 w-full m-auto px-5 pt-4 pb-5 mt-4 rounded-xl"
            style="box-shadow: 5px 5px 20px 5px rgba(0, 0, 0, 0.1); width: 500px">
            <h4 class="mb-4 mt-2" style="color: #39A900">
                <strong>Editar mi perfil </strong>
            </h4>
            <form class="row px-10" @submit.prevent="updateUser">
                <div class="col-12 mb-3">
                    <label for="name" class="font-bold">Nombre</label>
                    <input v-model="form.name" type="text" class="form-control" id="name">
                    <div class="text-red-400" v-if="form.errors.name">
                        {{ AppFunctions.getErrorTranslate(AppFunctions.Errors.Field) }}
                    </div>
                </div>
                <div class="col-12 mb-3">
                    <label for="email" class="font-bold">Email</label>
                    <input v-model="form.email" type="email" class="form-control" id="email">
                    <div class="text-red-400" v-if="form.errors.email">
                        {{ AppFunctions.getErrorTranslate(AppFunctions.Errors.Email) }}
                    </div>
                </div>
                <div class="col-12 mb-3">
                    <label for="document" class="font-bold">Documento</label>
                    <input v-model.number="form.document" type="number" class="form-control" id="document">
                    <div class="text-red-400" v-if="form.errors.document">
                        {{ AppFunctions.getErrorTranslate(AppFunctions.Errors.Field) }}
                    </div>
                </div>
                <div class="col-12 mb-16">
                    <label for="phone" class="font-bold">Teléfono</label>
                    <input v-model.number="form.phone" type="number" class="form-control" id="phone">
                    <div class="text-red-400" v-if="form.errors.phone">
                        {{ AppFunctions.getErrorTranslate(AppFunctions.Errors.Field) }}
                    </div>
                </div>
                <button :disabled="form.processing" type="submit" class="col-12 btn py-2 mb-3 text-white"
                    :style="`background-color: #39A900`">
                    <strong>Actualizar</strong>
                </button>
                <button data-bs-toggle="modal" data-bs-target="#modalChangePassword" type="button"
                    class="col-12 btn py-2 mb-3 text-white" :style="`background-color: #FF6624`">
                    <strong>Cambiar contraseña</strong>
                </button>

            </form>
        </div>

        <div class="modal fade" id="modalChangePassword" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" style="width: 400px; height: 600px;">
                <div class="modal-content position-relative p-3" style="max-height: 600px;">
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
                                <div class="text-red-400" v-if="passwordForm.errors.oldPassword">
                                    {{ AppFunctions.getErrorTranslate(AppFunctions.Errors.Field) }}
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="font-bold">Contraseña nueva</label>
                                <input v-model="passwordForm.password" type="password" class="form-control"
                                    id="password">
                                <div class="text-red-400" v-if="passwordForm.errors.password">
                                    {{ AppFunctions.getErrorTranslate(AppFunctions.Errors.Field) }}
                                </div>
                            </div>
                            <div class="mb-16">
                                <label for="password_confirmation" class="font-bold">Confirmar Contraseña</label>
                                <input v-model="passwordForm.password_confirmation" type="password" class="form-control"
                                    id="password_confirmation">
                                <div class="text-red-400" v-if="passwordForm.errors.password_confirmation">
                                    {{ AppFunctions.getErrorTranslate(AppFunctions.Errors.Field) }}
                                </div>
                            </div>

                            <div class="row justify-center p-3">
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
