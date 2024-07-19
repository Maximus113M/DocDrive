<template>
    <div class="container py-5">
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-5">

            <div class="col">
                <div class="folder border-3 rounded-4 py-3">
                    <svg xmlns="http://www.w3.org/2000/svg" height="84px" viewBox="0 -960 960 960" width="84px"
                        fill="#000000">
                        <path
                            d="M453-280h60v-166h167v-60H513v-174h-60v174H280v60h173v166Zm27.27 200q-82.74 0-155.5-31.5Q252-143 197.5-197.5t-86-127.34Q80-397.68 80-480.5t31.5-155.66Q143-709 197.5-763t127.34-85.5Q397.68-880 480.5-880t155.66 31.5Q709-817 763-763t85.5 127Q880-563 880-480.27q0 82.74-31.5 155.5Q817-252 763-197.68q-54 54.31-127 86Q563-80 480.27-80Z" />
                    </svg>
                    <h6 class="py-1 text-gray-400"><strong>Nueva Vigencia</strong></h6>

                </div>
            </div>

            <!-- FOLDER DESIGN -->
            <div class="col position-relative">
                <Link :href="route('password.request')" class="text-decoration-none">
                <div class="folder border-3 rounded-4 py-1">

                    <div class="position-absolute top-1 end-8" @click="onClicks">
                        <FoldersDropdown align="right" width="45" >
                            <template #trigger>
                                <span class="inline-flex rounded-md">
                                    <button type="button"
                                        class="inline-flex items-center px-3 py-2 leading-4 transition ease-in-out duration-150">

                                        <svg xmlns="http://www.w3.org/2000/svg" height="30px" viewBox="0 -960 960 960"
                                            width="30px" fill="#434343">
                                            <path
                                                d="M479.86-160Q460-160 446-174.14t-14-34Q432-228 446.14-242t34-14Q500-256 514-241.86t14 34Q528-188 513.86-174t-34 14Zm0-272Q460-432 446-446.14t-14-34Q432-500 446.14-514t34-14Q500-528 514-513.86t14 34Q528-460 513.86-446t-34 14Zm0-272Q460-704 446-718.14t-14-34Q432-772 446.14-786t34-14Q500-800 514-785.86t14 34Q528-732 513.86-718t-34 14Z" />
                                        </svg>
                                    </button>
                                </span>
                            </template>

                            <template #content>
                                <BreezeDropdownLink :href="route('logout')" method="post" as="button">
                                    Editar
                                </BreezeDropdownLink>
                                <BreezeDropdownLink :href="route('logout')" method="post" as="button">
                                    Eliminar
                                </BreezeDropdownLink>
                            </template>
                        </FoldersDropdown>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" height="80px" viewBox="0 -960 960 960" width="80px"
                        fill="#FEC63D">
                        <path
                            d="M140-160q-24 0-42-18.5T80-220v-520q0-23 18-41.5t42-18.5h281l60 60h339q23 0 41.5 18.5T880-680v460q0 23-18.5 41.5T820-160H140Z" />
                    </svg>

                    <div class="validity-font">
                        Vigencia
                    </div>
                    <h3 class="text-black"><strong>2024</strong></h3>
                </div>
            </Link>
            </div>

            <!-- FOLDER DESIGN -->

            <div class="col">
                <div class="folder border-3 rounded-4 py-1">
                    <svg xmlns="http://www.w3.org/2000/svg" height="80px" viewBox="0 -960 960 960" width="80px"
                        fill="#FEC63D">
                        <path
                            d="M140-160q-24 0-42-18.5T80-220v-520q0-23 18-41.5t42-18.5h281l60 60h339q23 0 41.5 18.5T880-680v460q0 23-18.5 41.5T820-160H140Z" />
                    </svg>
                    <div class="validity-font">
                        Vigencia
                    </div>
                    <h3><strong>2023</strong></h3>
                </div>
            </div>

            <div class="col">
                <div class="folder border-3 rounded-4 py-1">
                    <svg xmlns="http://www.w3.org/2000/svg" height="80px" viewBox="0 -960 960 960" width="80px"
                        fill="#FEC63D">
                        <path
                            d="M140-160q-24 0-42-18.5T80-220v-520q0-23 18-41.5t42-18.5h281l60 60h339q23 0 41.5 18.5T880-680v460q0 23-18.5 41.5T820-160H140Z" />
                    </svg>
                    <div class="validity-font">
                        Vigencia
                    </div>
                    <h3><strong>2022</strong></h3>
                </div>
            </div>

            <div class="col">
                <div class="p-3 border bg-light">Row column</div>
            </div>
        </div>
    </div>

    <div class="w-3/12 pt-32 m-auto">

        <Head title="Log in" />


        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>
        <div class="card p-5 col-12">
            <h3 class="font-bold pb-10">Inicio de sesión</h3>

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

                <div class="block mt-4">
                    <label class="flex items-center">
                        <BreezeCheckbox name="remember" v-model:checked="form.remember" />
                        <span class="ml-2 text-sm text-gray-600">Recuerdame</span>
                    </label>
                </div>

                <div class="flex items-center justify-end mt-4">
                    <Link v-if="canResetPassword" :href="route('password.request')"
                        class="underline text-sm text-gray-600 hover:text-gray-900">
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
import FoldersDropdown from '@/Shared/FoldersDropdown.vue';
import BreezeDropdownLink from '@/Components/DropdownLink.vue';

import BreezeButton from '@/Components/Button.vue';
import BreezeCheckbox from '@/Components/Checkbox.vue';
import BreezeGuestLayout from '@/Layouts/Guest.vue';
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