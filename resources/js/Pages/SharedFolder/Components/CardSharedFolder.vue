<script setup>
import { Link, useForm, usePage } from '@inertiajs/inertia-vue3';
import FoldersDropdown from '@/Shared/FoldersDropdown.vue';
import Icon from '@/Shared/Icon.vue';
import { AppFunctions } from '@/core/appFunctions';
import { CustomAlertsService } from '@/services/customAlerts';


const props = defineProps({
    id: { type: Number },
    name: { type: String, required: true },
});

const form = useForm({
    name: props.name,
})

const onClicks = (event) => {
    event.preventDefault();
}

const idModal = "modal-update-".concat(props.id)


const update = () => {
    form.put(route("shared.folder.update", { "folderID": props.id }), {
        onSuccess: () => showMessage(),
    })
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
    closeModalUpdate()
}

const openModalUpdate = () => {
    form.clearErrors();
    form.reset();
    const modal = document.getElementById(idModal)
    const modalBootstrap = new bootstrap.Modal(modal)
    modalBootstrap.show()
}


const closeModalUpdate = () => {
    const modal = document.getElementById(idModal)
    const modalBootstrap = bootstrap.Modal.getInstance(modal)
    modalBootstrap.hide()
}

const openModalDelete = () => {

    CustomAlertsService.deleteConfirmAlert({
        title: 'Eliminar el recurso compartido',
        text: `¿Deseas eliminar el recurso compartido ? Esta acción no se puede revertir`
    }).then((result) => {
        if (result.isConfirmed) {
            form.delete(`/shared/folder/${props.id}/destroy`, {
                onSuccess: () => {
                    showMessage()
                },
                onError: () => {
                    CustomAlertsService.generalAlert({
                        title: 'Error',
                        text: `Ha ocurrido un error al eliminar el recurso compartido`,
                        icon: "error",
                        isToast: true,
                    })
                }
            })
        }
    })

}

</script>

<template>
    <!-- DESIGN -->
    <div class="col position-relative" style="max-width: 300px;">

        <Link :href="route('shared.index', { 'folderID': id })" class="text-decoration-none">

        <div class="d-flex flex-column justify-center align-items-center border-3 rounded-4 pt-1 pb-2 bg-white h-40"
            style="box-shadow: 5px 5px 10px 2px rgba(0, 0, 0, 0.06);">
            <!-- BODY -->
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
                            @click="openModalUpdate">
                            Editar
                        </button>
                        <button
                            class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                            @click="openModalDelete">
                            Eliminar
                        </button>
                    </template>
                </FoldersDropdown>
            </div>
            <Icon name="sharedFolder" />


            <div v-if="!folder" class="validity-font">
                Recurso compartido
            </div>
            <div class="max-h-12 max-w-44 overflow-hidden text-center text-lg">
                <div v-if="!folder" class="text-black text-wrap text-ellipsis">
                    <strong> {{ props.name.length > 40
                        ?
                        `${props.name.substring(0, 40)}...` : props.name }}
                    </strong>
                </div>
                <div v-else class="text-black text-wrap text-ellipsis">
                    <strong> {{ props.name.length > 40 ?
                        `${props.name.substring(0, 40)}...` : props.name }}
                    </strong>
                </div>

            </div>
        </div>
        </Link>
    </div>
    <!-- PROJECT DESIGN -->



    <!-- MODAL EDIT SHARED FOLDER-->
    <div class="modal fade" :id="`modal-update-${props.id}`" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" style="width: 380px">
            <div class="modal-content position-relative p-3">
                <div class="d-flex flex-row justify-center px-3">
                    <h4 class="my-3" style="color: #39A900;">
                        <strong>Actualizar Recursos</strong>
                    </h4>

                    <button type="button" class="btn-close position-absolute top-1 end-3" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body px-3">
                    <form @submit.prevent="update">
                        <div class="mb-5">
                            <label for="name" class="font-bold form-label">Ingrese el nombre:</label>
                            <input v-model="form.name" type="text" class="form-control" id="name">
                            <div v-if="form.errors.name" class="text-red-400 text-center">
                                {{ AppFunctions.getErrorTranslate(AppFunctions.Errors.Field) }}
                            </div>
                        </div>


                        <div class="row justify-center p-3 ">
                            <button type="submit" class="btn py-2"
                                style="background-color: #39A900; color: white; "><strong>Actualizar</strong></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</template>

<style scoped>
.validity-font {
    color: #9E9E9E;
    font-size: 15px;
}
</style>
