<script setup>
import BreezeButton from '@/Components/Button.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import { Head, useForm } from '@inertiajs/inertia-vue3';

defineProps({
    status: String,
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <div class="flex justify-center h-screen w-full">

        <Head title="Recuperar contraseña" />
        <div class="card w-96 pt-32 m-auto p-5" style="box-shadow: 5px 5px 10px 2px rgba(0, 0, 0, 0.08);">
            <div class="font-bold text-2xl mb-3">
                Recuperar contraseña
            </div>

            <div class="mb-4 text-sm text-gray-600">
                ¿Olvidaste la contraseña? No hay problema. Ingresa tu correo electrónico para enviar un código de
                restablecimiento.
            </div>

            <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                {{ status }}
            </div>

            <BreezeValidationErrors class="mb-4" />

            <form @submit.prevent="submit">
                <div>
                    <BreezeLabel for="email" value="Correo" />
                    <BreezeInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required
                        autofocus autocomplete="username" />
                </div>

                <div class="flex justify-center mt-5 mb-2">
                    <BreezeButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Enviar link de confirmación
                    </BreezeButton>
                </div>
            </form>
        </div>

    </div>
</template>
