<template>
    <!-- DOCUMENT DESIGN -->
    <div class="col position-relative" style="max-width: 300px;">

        <!-- <Link :href="route('project.index', { 'validityYear': currentYear, 'projectID': props.project.id })" 
            class="text-decoration-none">-->

        <!-- BODY -->
        <div class="d-flex flex-column justify-center align-items-center border-3 rounded-4 py-1 bg-white h-40">
            <!-- Options -->
            <div v-if="authUser != null && (authUser.role.name == 'admin'
                || (isAssociatedUser))" class="position-absolute top-1 end-1" @click="onClicks">
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
            <!-- Options -->

            <Icon :name="documentType" />


            <div class="validity-font">
                <div class="max-h-20 max-w-44 overflow-hidden text-center text-black text-wrap text-ellipsis">
                    <div class=""><strong> {{ props.project.name.length > 40 ?
                        `${props.project.name.substring(0, 40)}...` : props.project.name }} </strong></div>
                </div>
            </div>
        </div>
        <!-- </Link> -->
    </div>
    <!-- DOCUMENT DESIGN -->

    <!-- MODAL EDITAR-->
    <div class="modal fade" :id="`modal-update-project-${props.project.id}`" tabindex="-1"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="width: 350px;">
            <div class="modal-content position-relative p-3" style="max-height: 800px;">
                <div class="d-flex flex-row justify-center px-3">
                    <h4 class="my-3" style="color: #39A900;"><strong>Actualizar Documento</strong></h4>

                    <button type="button" class="btn-close position-absolute top-1 end-3" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body px-3">
                    <form @submit.prevent="updateProject">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre:</label>
                            <input v-model="form.name" type="text" class="form-control" id="name">
                            <div v-if="form.errors.name">{{ AppFunctions.getErrorTranslate(AppFunctions.Errors.Field) }}</div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link, useForm, usePage } from '@inertiajs/inertia-vue3';
import FoldersDropdown from '@/Shared/FoldersDropdown.vue';
import Icon from '@/Shared/Icon.vue';
import { onMounted, ref } from 'vue';
import { CustomAlertsService } from '@/services/customAlerts';
import { DocumentModel } from '@/models/documentModel';
import { AppFunctions } from '@/core/appFunctions';

const props = defineProps({
    document: { type: DocumentModel, required: true },
    project: { type: Object, required: true },
});

const documentForm = useForm({
    name: props.document.name,
    description: props.document.description,
})
const isAssociatedUser = ref(null);
const authUser = usePage().props.value.auth.user;

const documentType= ref<string>(props.document.format)

onMounted(() => {
    isAssociatedUser.value = verifiyAssociatedUser()
})

const onClicks = (event) => {
    event.preventDefault();
}

const verifiyAssociatedUser = () => {
    if (authUser || !props.project.users) {
        return false;
    }
    for (let index = 0; index < props.project.users.length; index++) {
        const user = props.project.users[index];
        if (user.id == authUser.id) {
            return true;
        }
    }
    return false;
}

const updateProject = () => {
    // TODO: CAMBIAR A LA RUTA VALIDA
    documentForm.put(route("project.update", { "projectID": props.document.id }), {
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
    const modal = document.getElementById("modal-update-document-" + props.document.id)
    const modalBootstrap = new bootstrap.Modal(modal)
    modalBootstrap.show()
}


const closeModalUpdate = () => {
    const modal = document.getElementById("modal-update-document-" + props.document.id)
    const modalBootstrap = bootstrap.Modal.getInstance(modal)
    modalBootstrap.hide()
}

const openModalDelete = () => {
    CustomAlertsService.deleteConfirmAlert({
        title: 'Eliminar Documento',
        text: '¿Deseas eliminar el documento seleccionado? Esta acción no se puede revertir'
    }).then((result) => {
        if (result.isConfirmed) {

            // TODO CAMBIAR A RUTA VERDADERA
            form.delete(route("project.destroy", { "projectID": props.document.id }), {
                onSuccess: () => {
                    showMessage()
                },
                onError: () => {
                    CustomAlertsService.generalAlert({
                        title: 'Error',
                        text: `Ha ocurrido un error al eliminar el documento`,
                        icon: "error",
                        isToast: true,
                    })
                }
            })
        }
    })
}
</script>

<style scoped>
.validity-font {
    color: #9E9E9E;
    font-size: 15px;
}
</style>
