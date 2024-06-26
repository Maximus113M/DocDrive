<script setup>
import { Link, useForm, usePage } from '@inertiajs/inertia-vue3';
import FoldersDropdown from '@/Shared/FoldersDropdown.vue';
import BreezeDropdownLink from '@/Components/DropdownLink.vue';
import Icon from '@/Shared/Icon.vue';
import { onMounted, ref } from 'vue';
import { CustomAlertsService } from '@/services/customAlerts';
import { AppFunctions } from '@/core/appFunctions';



const props = defineProps({
    folder: { type: Object },
    project: { type: Object },
    currentYear: { type: String },
    visualizationsRole: { type: Array },
    isSharedResource: { type: Boolean }
});


const form = useForm({
    name: !props.folder ? props.project.name : props.folder.name,
    description: !props.folder ? props.project.description : props.folder.description,
    startDate: !props.folder ? props.project.startDate : null,
    endDate: !props.folder ? props.project.endDate : null,
    visualizationRoleSelected: !props.folder ? props.project.visualization_role_id : props.folder.visualization_role_id,
    isSharedResource: !props.isSharedResource ?? null
    //target: project.target,
})


const isAssociatedUser = ref(null);

const authUser = usePage().props.value.auth.user ?? null;

onMounted(() => {
    isAssociatedUser.value = verifiyAssociatedUser()
})


const onClicks = (event) => {
    event.preventDefault();
}
const idModal = "modal-update-".concat(props.folder ? props.folder.id : props.project.id)

const verifiyAssociatedUser = () => {
    if (!props.project) {
        return false
    }
    if (authUser == null || !props.project.users) {
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

const nameRoleVisualization = {
    "private": "Privado",
    "public": "Público",
    "general-public": "Publico en general"
}

const update = () => {
    if (!props.project && props.isSharedResource) {
        form.put(route("shared.folder.update", { "folderID": props.folder.id }), {
            onSuccess: () => showMessage(),
        })
    } else if (props.folder && !props.isSharedResource) {
        form.put(route("folder.update", { "folderID": props.project.id, "projectID": props.project.id }), {
            onSuccess: () => showMessage(),
        })
    } else {
        form.put(route("project.update", { "projectID": props.project.id }), {
            onSuccess: () => showMessage(),
        })
    }
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
    console.log(modal);
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
        title: 'Eliminar Proyecto',
        text: '¿Deseas eliminar el proyecto seleccionado? Esta acción no se puede revertir'
    }).then((result) => {
        if (result.isConfirmed) {
            form.delete(route("project.destroy", { "projectID": props.project.id }), {
                onSuccess: () => {
                    showMessage()
                },
                onError: () => {
                    CustomAlertsService.generalAlert({
                        title: 'Error',
                        text: `Ha ocurrido un error al eliminar el proyecto`,
                        icon: "error",
                        isToast: true,
                    })
                }
            })
        }
    })
}
const selectRoute = () => {
    if (props.folder && !props.isSharedResource) {
        return `/${props.currentYear}/projects/${props.project.id}/folders/${props.folder.id}`
    } else if (props.folder && props.isSharedResource) {
        return `/shared/${props.folder.id}`
    } else {
        return `/${props.currentYear}/projects/${props.project.id}`
    }
}



</script>

<template>
    <!-- PROJECT DESIGN -->
    <div class="col position-relative" style="max-width: 300px;">

        <Link method="get" :href="selectRoute()" class="text-decoration-none">
        <div class="d-flex flex-column justify-center align-items-center border-3 rounded-4 py-1 bg-white h-40">
            <div class="cursor-auto absolute left-0 ml-2" data-toggle="tooltip" data-placement="top"
                title="¡Datos del proyecto incompleto!"
                v-if="props.project && props.project.isIncomplete && isAssociatedUser">
                <svg xmlns="http://www.w3.org/2000/svg" width="35px" height="35px" viewBox="0 0 24 24">
                    <g fill="none">
                        <path
                            d="M24 0v24H0V0zM12.593 23.258l-.011.002l-.071.035l-.02.004l-.014-.004l-.071-.035c-.01-.004-.019-.001-.024.005l-.004.01l-.017.428l.005.02l.01.013l.104.074l.015.004l.012-.004l.104-.074l.012-.016l.004-.017l-.017-.427c-.002-.01-.009-.017-.017-.018m.265-.113l-.013.002l-.185.093l-.01.01l-.003.011l.018.43l.005.012l.008.007l.201.093c.012.004.023 0 .029-.008l.004-.014l-.034-.614c-.003-.012-.01-.02-.02-.022m-.715.002a.023.023 0 0 0-.027.006l-.006.014l-.034.614c0 .012.007.02.017.024l.015-.002l.201-.093l.01-.008l.004-.011l.017-.43l-.003-.012l-.01-.01z" />
                        <path fill="#e5a50a"
                            d="M12 2c5.523 0 10 4.477 10 10s-4.477 10-10 10S2 17.523 2 12S6.477 2 12 2m0 13a1 1 0 1 0 0 2a1 1 0 0 0 0-2m0-9a1 1 0 0 0-.993.883L11 7v6a1 1 0 0 0 1.993.117L13 13V7a1 1 0 0 0-1-1" />
                    </g>
                </svg>
            </div>
            <!-- BODY -->
            <div v-if="authUser != null && (authUser.role.name == 'admin'
                || (authUser.role.name == 'investigator' && isAssociatedUser))" class="position-absolute top-1 end-1"
                @click="onClicks">
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
            <Icon name="project" />


            <div v-if="!folder" class="validity-font">
                Proyecto
            </div>
            <div class="max-h-12 max-w-44 overflow-hidden text-center">
                <h5 v-if="!folder" class="text-black text-wrap text-ellipsis"><strong> {{ props.project.name.length > 25
                    ?
                    `${props.project.name.substring(0, 25)}...` : props.project.name }} </strong></h5>
                <h5 v-else class="text-black text-wrap text-ellipsis"><strong> {{ props.folder.name.length > 25 ?
                    `${props.folder.name.substring(0, 25)}...` : props.folder.name }} </strong></h5>

            </div>
        </div>
        </Link>
    </div>
    <!-- PROJECT DESIGN -->


    <!--  -->

    <!-- MODAL EDIT PROJECT -->
    <div class="modal fade"
        :id="!props.folder ? `modal-update-${props.project.id}` : `modal-update-${props.folder.id}`" tabindex="-1"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="width: 600px;">
            <div class="modal-content position-relative p-3">
                <div class="d-flex flex-row justify-center px-3">
                    <h4 class="my-3" style="color: #39A900;"><strong>Actualizar proyecto</strong></h4>

                    <button type="button" class="btn-close position-absolute top-1 end-3" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body px-3">
                    <form class="sm:grid sm:grid-cols-2" @submit.prevent="update">
                        <div class="col-span-2 mb-3">
                            <label for="name" class="font-bold form-label">Ingrese el nombre:</label>
                            <input v-model="form.name" type="text" class="form-control" id="name">
                            <div v-if="form.errors.name" class="text-red-400 text-center">
                                {{ AppFunctions.getErrorTranslate(AppFunctions.Errors.Field) }}
                            </div>
                        </div>

                        <div v-if="!props.folder" class="mb-3">
                            <label for="startDate" class="font-bold form-label">Ingrese la fecha de inicio:</label>
                            <input v-model="form.startDate" type="date" class="form-control" id="startDate">
                            <div v-if="form.errors.startDate" class="text-center">
                                {{ AppFunctions.getErrorTranslate(AppFunctions.Errors.startDate) }}
                            </div>
                        </div>
                        <div v-if="!props.folder" class="mb-3">
                            <label for="endDate" class="font-bold form-label">Ingrese la fecha de fin:</label>
                            <input v-model="form.endDate" type="date" class="form-control" id="endDate">
                            <div v-if="form.errors.endDate" class="text-red-400">
                                {{ AppFunctions.getErrorTranslate(AppFunctions.Errors.endDate) }}
                            </div>
                        </div>

                        <div v-if="!props.isSharedResource" class="mb-3">
                            <label class="font-bold form-label">Visualización</label>
                            <select v-model="form.visualizationRoleSelected" class="form-select">
                                <option v-for="vRole in visualizationsRole" :value="vRole.id" :key="vRole.id">{{
                                    nameRoleVisualization[vRole.name] }}</option>
                            </select>
                            <div v-if="form.errors.visualizationRoleSelected" class="text-red-400 text-center">
                                {{ AppFunctions.getErrorTranslate(AppFunctions.Errors.Field) }}
                            </div>
                        </div>

                        <div class="col-span-2 mb-3">
                            <label for="description" class="font-bold form-label">Ingrese la descripción:</label>
                            <textarea v-model="form.description" type="text-area" class="form-control h-48 max-h-48"
                                id="description" />
                            <div v-if="form.errors.description" class="text-red-400 text-center">
                                {{ AppFunctions.getErrorTranslate(AppFunctions.Errors.Field) }}
                            </div>
                        </div>
                        <div class="col-span-2 row justify-center p-3 ">
                            <button type="submit" class="btn py-2"
                                style="background-color: #39A900; color: white; "><strong>Actualizar
                                    proyecto</strong></button>
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
