<script setup>
import BreezeButton from '@/Components/Button.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import { CustomAlertsService } from '@/services/customAlerts';
import { Head, useForm, usePage } from '@inertiajs/inertia-vue3';

const props = defineProps({
    email: String,
    token: String,
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    if (!validConfirmPassowrd()) {
        form.errors.password = "Las contraseñas no coiciden"
        form.errors.password_confirmation = "Las contraseñas no coiciden"
        return
    }
    form.post(route('password.update'), {
        onFinish: () => {
            form.reset('password', 'password_confirmation')
            showErrorMessage()
        },
    });
};

const validConfirmPassowrd = () => {
    return form.password == form.password_confirmation
        || !form.password || !form.password_confirmation
}

const showErrorMessage = () => {
    const errorMessage = usePage().props.value.flash.errorMessage

    if (errorMessage) {
        CustomAlertsService.generalAlert({
            title: 'Error',
            text: errorMessage,
            icon: "error",
            isToast: true,
        })
    } 
}




</script>

<template>
    <div class="flex justify-center h-screen w-full">

        <Head title="Reset Password" />

        <BreezeValidationErrors class="mb-4" />


        <div class="card w-96 pt-32 m-auto p-5" style="box-shadow: 5px 5px 10px 2px rgba(0, 0, 0, 0.08);">
            
            <div class="font-bold text-2xl mb-3">
                Cambia tu contraseña
            </div>

            <div class="mb-4 text-sm text-gray-600">
                Digita una nueva contraseña
            </div>
            
            
            <form @submit.prevent="submit">
                <div>
                    <BreezeLabel for="email" value="Email" />
                    <BreezeInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required
                        autofocus autocomplete="username" />
                    <div class="text-xs text-red-500" v-if="form.errors.email">{{ form.errors.email }}</div>
                </div>

                <div class="mt-4">
                    <BreezeLabel for="password" value="Password" />
                    <BreezeInput id="password" type="password" class="mt-1 block w-full" v-model="form.password"
                        required autocomplete="new-password" />
                    <div class="text-xs text-red-500" v-if="form.errors.password">{{ form.errors.password }}</div>
                </div>

                <div class="mt-4">
                    <BreezeLabel for="password_confirmation" value="Confirm Password" />
                    <BreezeInput id="password_confirmation" type="password" class="mt-1 block w-full"
                        v-model="form.password_confirmation" required autocomplete="new-password" />
                    <div class="text-xs text-red-500" v-if="form.errors.password_confirmation">{{ form.errors.password_confirmation }}</div>
                </div>

                <div class="flex items-center justify-end mt-4">
                    <BreezeButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Reset Password
                    </BreezeButton>
                </div>
            </form>
        </div>



    </div>
</template>
