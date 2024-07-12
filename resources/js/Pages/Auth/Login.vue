<template>
    <div class="lg:w-4/12 md:w-5/12 sm:w-6/12 w-9/12 pt-32 m-auto">

        <Head title="Log in" />


        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>
        <div class="card p-5 col-12">
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
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';

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
const onClicks= (event)=>{
    event.preventDefault();
}
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
</style>