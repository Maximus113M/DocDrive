<script setup>
import AuthLayout from '@/Layouts/Authenticated.vue';
import { CustomAlertsService } from '@/services/customAlerts';
import { useForm } from '@inertiajs/inertia-vue3';

const { user } = defineProps({
    user: Object
})

const validConfirmPassowrd = () => {
    return form.password == form.password_confirmation || !form.password || !form.password_confirmation
}

const form = useForm({
    name: user.name,
    email: user.email,
    document: user.document,
    phone: user.phone,
    password: "",
    password_confirmation: null
})


const role = user.role.name;

const updateUser = () => {
    if (!validConfirmPassowrd()) {
        form.errors.password = "Las contraseñas no coiciden"
        return
    }
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

const showMessage = () => {
    CustomAlertsService.successConfirmAlert({
        title: "Se ha actualizado correctamente tus datos",
    })
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
                <div class="mb-3">
                    <label for="password" class="font-bold">Contraseña</label>
                    <input v-model="form.password" type="password" class="form-control" id="password">
                    <div class="text-red-400" v-if="form.errors.password">{{
                        form.errors.password }}</div>
                </div>
                <div class="mb-3">
                    <label for="password_confirmation" class="font-bold">Confirmar Contraseña</label>
                    <input v-model="form.password_confirmation" type="password" class="form-control"
                        id="password_confirmation">
                    <div class="text-red-400" v-if="form.errors.password_confirmation">{{
                        form.errors.password_confirmation }}</div>
                </div>
                <div class="col-span-2 w-full sm:w-2/5 row justify-center p-3 mt-2">
                    <button :disabled="form.processing" type="submit" class="btn py-2 text-white"
                        :style="`background-color: #39A900`">
                        <strong>Actualizar</strong>
                    </button>
                </div>
            </form>
        </div>

    </AuthLayout>


</template>
