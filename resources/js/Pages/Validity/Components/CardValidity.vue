<template>
    <!-- FOLDER DESIGN -->
    <div class="col position-relative" style="max-width: 300px;">
        <Link :href="route('validity.projects', { 'validityYear': year })" class="text-decoration-none">
        <div class="d-flex flex-column align-items-center border-3 rounded-4 py-1 bg-white"
            style="box-shadow: 5px 5px 10px 2px rgba(0, 0, 0, 0.06);">

            <div v-if="$page.props.auth.user != null && $page.props.auth.user.role.name == 'admin'"
                class="position-absolute top-1 end-1" @click="onClicks">
                <FoldersDropdown align="right" width="45">
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
                        <button
                            class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                            data-bs-toggle="modal" data-bs-target="#modal" @click="openModal">
                            Editar
                        </button>
                        <button
                            class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                            data-bs-toggle="modal" data-bs-target="#modal" @click="openModalDelete">
                            Eliminar
                        </button>
                    </template>
                </FoldersDropdown>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" height="80px" viewBox="0 -960 960 960" width="80px" fill="#FEC63D">
                <path
                    d="M140-160q-24 0-42-18.5T80-220v-520q0-23 18-41.5t42-18.5h281l60 60h339q23 0 41.5 18.5T880-680v460q0 23-18.5 41.5T820-160H140Z" />
            </svg>

            <div class="validity-font">
                Vigencia
            </div>
            <h3 class="text-black"><strong>{{ year }}</strong></h3>
        </div>
        </Link>
    </div>
    <!-- FOLDER DESIGN -->


    <!-- MODAL EDITAR-->
    <div class="modal fade" :id="`modal-${id}`" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="width: 350px; height: 600px;">
            <div class="modal-content position-relative p-3" style="max-height: 400px;">
                <div class="d-flex flex-row justify-center px-3">
                    <h4 class="my-3" style="color: #39A900;"><strong>Actualizar vigencia</strong></h4>

                    <button type="button" class="btn-close position-absolute top-1 end-3" data-bs-dismiss="modal"
                        aria-label="Close">
                    </button>
                </div>
                <div class="modal-body px-3">
                    <form @submit.prevent="updateValidity">
                        <div class="mb-3">
                            <label for="year" class="form-label">Ingrese el año:</label>
                            <input v-model="form.year" type="number" class="form-control" id="year">
                            <div v-if="form.errors.year">{{ form.errors.year }}</div>
                        </div>
                        <div class="row justify-center p-3 mt-5">
                            <button type="submit" class="btn py-2" style="background-color: #39A900; color: white; ">
                                <strong>Actualizarvigencia</strong>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</template>

<script setup>
import { Link, usePage } from '@inertiajs/inertia-vue3';
import FoldersDropdown from '@/Shared/FoldersDropdown.vue';
import { useForm } from '@inertiajs/inertia-vue3'
import { CustomAlertsService } from '@/services/customAlerts';

const props = defineProps(['year', 'id']);

const form = useForm({
    year: props.year,
})


const openModal = () => {
    const modal = document.getElementById("modal-" + props.id)
    const modalBootstrap = new bootstrap.Modal(modal)
    modalBootstrap.show()
}

const openModalDelete = () => {
    CustomAlertsService.deleteConfirmAlert({
        title: 'Eliminar vigencia',
        text: '¿Deseas eliminar la vigencia seleccionado? Esta acción no se puede revertir'
    }).then((result) => {
        if (result.isConfirmed) {
            form.delete(route("validity.destroy", { "validityID": props.id }), {
                onSuccess: () => {
                    showMessage()
                },
                onError: () => {
                    CustomAlertsService.generalAlert({
                        title: 'Error',
                        text: `Ha ocurrido un error al eliminar la vigencia`,
                        icon: "error",
                        isToast: true,
                    })
                }
            })
        }
    })
}

const updateValidity = () => {
    form.put(route("validity.update", { "validityID": props.id }), {
        onSuccess: () => closeModal()
    })
}

const closeModal = () => {
    const modal = document.getElementById("modal-" + props.id)
    const modalBootstrap = bootstrap.Modal.getInstance(modal)
    modalBootstrap.hide()
}


const showMessage = () => {
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

const onClicks = (event) => {
    event.preventDefault();
}

</script>

<style scoped>
.validity-font {
    color: #9E9E9E;
    font-size: 15px;
}
</style>
