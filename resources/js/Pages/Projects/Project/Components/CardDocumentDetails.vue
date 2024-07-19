<template>
    <!-- DOCUMENT DESIGN -->
    <div class="col position-relative" style="max-width: 300px;">

        <a class="no-underline" target="_blank" :href="documentType === 'link' ? document.documentPath : showDocumentRoute">
            <!-- BODY -->
            <div class="d-flex flex-column justify-center align-items-center border-3 rounded-4 py-1 bg-white h-40"
                style="box-shadow: 5px 5px 10px 2px rgba(0, 0, 0, 0.06);">
                <!-- Options -->
                <div v-if="authUser != null && (authUser.role.name == 'admin'
                    || (isAssociatedUser)) && project" class="position-absolute top-1 end-1" @click="onClicks">
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
                            <button v-if="props.project"
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
                <!-- End-Options -->

                <Icon :name="documentType" />

                <div class="validity-font">
                    <div class="max-h-20 max-w-44 overflow-hidden text-center text-black text-wrap text-ellipsis">
                        <div class="">
                            <strong> {{ props.document.name.length > 40 ?
                                `${props.document.name.substring(0, 40)}...` : props.document.name }}
                            </strong>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <!-- DOCUMENT DESIGN -->

    <!-- MODAL EDITAR-->
    <div class="modal fade" :id="`modal-update-document-${props.document.id}`" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" style="width: 350px;">
            <div class="modal-content position-relative p-3" style="max-height: 800px;">
                <div class="d-flex flex-row justify-center px-3">
                    <h4 class="my-3" style="color: #39A900;">
                        <strong>Actualizar Documento</strong>
                    </h4>

                    <button type="button" class="btn-close position-absolute top-1 end-3" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body px-3">
                    <form @submit.prevent="update">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre:</label>
                            <input v-model="documentForm.name" type="text" class="form-control" id="name">
                            <div v-if="documentForm.errors.name">{{
                                AppFunctions.getErrorTranslate(AppFunctions.Errors.Field) }}</div>
                        </div>
                        <div v-if="props.project" class="mb-3">
                            <label class="font-bold form-label">Visualización</label>
                            <select v-model="documentForm.visualizationRoleSelected" class="form-select">
                                <option v-for="vRole in visualizationsRole" :value="vRole.id" :key="vRole.id">{{
                                    nameRoleVisualization[vRole.name] }}</option>
                            </select>
                            <div v-if="documentForm.errors.visualizationRoleSelected" class="text-red-400 text-center">
                                {{ AppFunctions.getErrorTranslate(AppFunctions.Errors.Field) }}
                            </div>
                        </div>
                        <div class="mb-3 inputs-file">
                            <div class="font-bold">Categorías</div>
                            <select v-model="documentForm.category" class="form-select">
                                <option v-for="category in props.documentCategories" :value="category.id"
                                    :key="category.id">
                                    {{category.name }}</option>
                            </select>
                        </div>

                        <div class="col-span-2 row justify-center p-3 ">
                            <button type="submit" class="btn py-2" style="background-color: #39A900; color: white; ">
                                <strong>Actualizar</strong>
                            </button>
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
    document: { type: /*DocumentModel*/Object, required: true },
    currentYear: { type: Number, },
    project: { type: Object, },
    folderID: { type: Number },
    visualizationsRole: { type: Array },
    documentCategories: { type: Array, required: true },
});

const documentForm = useForm({
    name: props.document.name,
    visualizationRoleSelected: props.document.visualization_role_id,
    category: props.document.categories?.length > 0 ? props.document.categories[0].id : null,
})
const isAssociatedUser = ref(null);
const authUser = usePage().props.value.auth.user;

const documentType = ref(props.document.format)

const showDocumentRoute = props.project ? route('file.index', {
    'validityYear': props.currentYear,
    'projectID': props.project.id,
    'documentID': props.document.id,
})
    : route('shared.file.index', {
        'folderID': props.folderID,
        'documentID': props.document.id,
    })

onMounted(() => {
    console.log(props.currentYear);
    isAssociatedUser.value = verifiyAssociatedUser()
    console.log('--------------------------------------')
    console.log(props.document);
})


const onClicks = (event) => {
    event.preventDefault();
}

const verifiyAssociatedUser = () => {
    if (!props.project) {
        return false;
    }
    if (!authUser || !props.project.users) {
        return false;
    }
    for (let index = 0; index < props.project.users.length; index++) {
        const user = props.project.users[index];
        if (user.id == authUser?.id) {
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
    if (props.project) {
        documentForm.put(route("document.update", {
            "projectID": props.project.id,
            "documentID": props.document.id,
        }), {
            onSuccess: () => {
                showMessage()
                closeModalUpdate()
            },
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
}

const idModal = "modal-update-document-".concat(props.document.id)


const openModalUpdate = () => {
    documentForm.clearErrors();
    documentForm.reset();
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
    let text = !props.project ? "desasociar" : "eliminar"
    let route = !props.project ? `/shared/${props.folderID}/document/${props.document.id}/destroy`
        : `/project/${props.project.id}/document/${props.document.id}/destroy`
    console.log(route);
    CustomAlertsService.deleteConfirmAlert({
        title: `${props.project ? 'Eliminar' : 'Desasociar'} Documento`,
        text: `¿Deseas ${text} el documento seleccionado? Esta acción no se puede revertir`
    }).then((result) => {
        if (result.isConfirmed) {
            documentForm.delete(route, {
                onSuccess: () => {
                    showMessage()
                },
                onError: () => {
                    CustomAlertsService.generalAlert({
                        title: 'Error',
                        text: `Ha ocurrido un error al ${text} el documento`,
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
