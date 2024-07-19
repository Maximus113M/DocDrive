<template>
    <div class="flex justify-center h-screen w-full">

        <Head title="Log in" />


        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>
        <div class="card w-96 pt-32 m-auto p-5" style="box-shadow: 5px 5px 10px 2px rgba(0, 0, 0, 0.08);">
            <h3 class="font-bold text-center pb-10">Inicio de sesión</h3>

            <BreezeValidationErrors class="mb-4" />

            <form @submit.prevent="submit">
                <div>
                    <BreezeLabel for="email" value="Correo" />
                    <BreezeInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required
                        autofocus autocomplete="username" />
                </div>

                <div class="mt-4">
                    <BreezeLabel for="password" value="Contraseña" />
                    <BreezeInput id="password" type="password" class="mt-1 block w-full" v-model="form.password"
                        required autocomplete="current-password" />
                </div>


                <div class="flex items-center justify-end mt-4">
                    <Link v-if="canResetPassword" :href="route('password.request')"
                        class="no-underline text-sm text-gray-600 hover:text-gray-900">
                    Olvidaste la contraseña?
                    </Link>

                    <BreezeButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Ingresar
                    </BreezeButton>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>

import BreezeButton from '@/Components/Button.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import { CustomAlertsService } from '@/services/customAlerts';
import { Head, Link, useForm, usePage } from '@inertiajs/inertia-vue3';
import { onBeforeMount, onMounted } from 'vue';

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};

const showSuccessMessage = () => {
    const flashMessage = usePage().props.value.flash.message
    const errorMessage = usePage().props.value.flash.errorMessage

    if (flashMessage) {
        CustomAlertsService.successConfirmAlert({
            title: flashMessage,
        })
    } else if (errorMessage) {
        CustomAlertsService.generalAlert({
            title: 'Error',
            text: errorMessage,
            icon: "error",
            isToast: true,
        })
    } 
}

onBeforeMount(() => {
    showSuccessMessage()
})

</script>

<style scoped>
.folder {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.validity-font {
    color: #9E9E9E;
    font-size: 15px;
}

.background-cidm{
    margin: auto; 
    background-image: linear-gradient(to bottom,
    rgba(255, 255, 255, 0.1),
    rgba(255, 255, 255, 0.1)) ,url(../../../../public/images/cidm.jpg);
    background-repeat: no-repeat;
    background-position: center top;
    background-size: 70%;
}
</style>