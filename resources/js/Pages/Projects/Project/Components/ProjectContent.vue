<template>
    <div class="w-full">
        <!-- Buscador -->
        <div class="py-3">
            <div class="ml-5 flex justify-start items-center">
                <Link class="mr-3" method="get"
                    :href="route('validity.projects', { 'validityYear': props.currentYear })">
                <Icon name="back" />
                </Link>

                <div class="text-2xl font-bold text-color-gray">
                    {{ `Vigencias/${props.currentYear}/${props.project.name}` }}
                </div>
            </div>
        </div>

        <div class="px-5 pt-1 row row-cols-2 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 row-cols-xxl-5 g-5">

            <button :v-if="authUser != null && (authUser?.role.name == 'admin'
                || (isAssociatedUser))" class="col" data-bs-toggle="modal" data-bs-target="#modalNewDocument">
                <div class="folder border-3 rounded-4 py-3 bg-white">
                    <svg xmlns="http://www.w3.org/2000/svg" height="84px" viewBox="0 -960 960 960" width="84px"
                        fill="#000000">
                        <path
                            d="M453-280h60v-166h167v-60H513v-174h-60v174H280v60h173v166Zm27.27 200q-82.74 0-155.5-31.5Q252-143 197.5-197.5t-86-127.34Q80-397.68 80-480.5t31.5-155.66Q143-709 197.5-763t127.34-85.5Q397.68-880 480.5-880t155.66 31.5Q709-817 763-763t85.5 127Q880-563 880-480.27q0 82.74-31.5 155.5Q817-252 763-197.68q-54 54.31-127 86Q563-80 480.27-80Z" />
                    </svg>
                    <h6 class="py-1 text-gray-400"><strong>Nuevo</strong></h6>
                </div>
            </button>

            <button name="investigator" :onclick="changeTypeUserToInvestigator" v-if="authUser != null && (authUser?.role.name == 'admin'
                || (isAssociatedUser))" class="col" data-bs-toggle="modal" data-bs-target="#modalAssociateUser">
                <div class="folder border-3 rounded-4 py-3 bg-white">
                    <svg xmlns="http://www.w3.org/2000/svg" height="84px" viewBox="0 -960 960 960" width="84px"
                        fill="#39A900">
                        <path
                            d="M453-280h60v-166h167v-60H513v-174h-60v174H280v60h173v166Zm27.27 200q-82.74 0-155.5-31.5Q252-143 197.5-197.5t-86-127.34Q80-397.68 80-480.5t31.5-155.66Q143-709 197.5-763t127.34-85.5Q397.68-880 480.5-880t155.66 31.5Q709-817 763-763t85.5 127Q880-563 880-480.27q0 82.74-31.5 155.5Q817-252 763-197.68q-54 54.31-127 86Q563-80 480.27-80Z" />
                    </svg>
                    <h6 class="py-1 text-gray-400"><strong>Asociar Investigador</strong></h6>
                </div>
            </button>

            <button name="collaborator" :onclick="changeTypeUserToCollaborator" v-if="authUser != null && (authUser?.role.name == 'admin'
                || (isAssociatedUser))" class="col" data-bs-toggle="modal" data-bs-target="#modalAssociateUser">
                <div class="folder border-3 rounded-4 py-3 bg-white">
                    <svg xmlns="http://www.w3.org/2000/svg" height="84px" viewBox="0 -960 960 960" width="84px"
                        fill="#FF6624">
                        <path
                            d="M453-280h60v-166h167v-60H513v-174h-60v174H280v60h173v166Zm27.27 200q-82.74 0-155.5-31.5Q252-143 197.5-197.5t-86-127.34Q80-397.68 80-480.5t31.5-155.66Q143-709 197.5-763t127.34-85.5Q397.68-880 480.5-880t155.66 31.5Q709-817 763-763t85.5 127Q880-563 880-480.27q0 82.74-31.5 155.5Q817-252 763-197.68q-54 54.31-127 86Q563-80 480.27-80Z" />
                    </svg>
                    <h6 class="py-1 text-gray-400"><strong>Asociar Colaborador</strong></h6>
                </div>
            </button>

            <div v-for="folder in props.project.folders">
                <ProjectCard :folder="folder" :project="project" :current-year="currentYear" />
            </div>
            <div v-for="document in props.project.documents">
                <CardDocumentDetails :document="document" :current-year="currentYear" :project="project" />
            </div>
        </div>
    </div>

    <!-- MODAL NEW FILE-FOLDER -->
    <div class="modal fade" id="modalNewDocument" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" style="width: 350px; height: 600px;">
            <div class="modal-content position-relative p-3" style="max-height: 400px;">
                <div class="d-flex flex-row justify-center px-3">
                    <h4 class="my-3" style="color: #39A900;"><strong>Nuevo</strong></h4>

                    <button type="button" class="btn-close position-absolute top-1 end-3" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body px-3">
                    <form @submit.prevent="upload">
                        <div class="flex mb-3">
                            <div class="form-check mr-4">
                                <input :onclick="changeInputCheck" ref="checkedUploadFile" class="form-check-input"
                                    type="radio" name="file" id="file" value="file" checked>
                                <label class="form-check-label" for="file">
                                    Archivo
                                </label>
                            </div>
                            <div class="form-check">
                                <input :onclick="changeInputCheck" ref="checkedCreateFolder" class="form-check-input"
                                    type="radio" name="file" value="folder" id="folder">
                                <label class="form-check-label" for="folder">
                                    Carpeta
                                </label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="font-bold">Visualización</label>
                            <br>
                            <select v-model="formUploadFile.visualizationRoleSelected" class="form-select">
                                <option v-for="vRole in visualizationsRole" :value="vRole.id" :key="vRole.id">{{
                                    nameRoleVisualization[vRole.name] }}</option>
                                <div v-if="formUploadFile.errors.visualizationRoleSelected">{{
                                    formUploadFile.errors.visualizationRoleSelected }}</div>
                            </select>
                        </div>
                        <div class="mb-3 inputs-folder" style="display: none;">
                            <label class="font-bold">Nombre</label>
                            <br>
                            <input v-model="formUploadFile.name" class="form-control" type="text" placeholder="Nombre">
                        </div>
                        <div class="mb-3 inputs-file">
                            <label for="formFile" class="font-bold form-label">Seleccionar archivo</label>
                            <input @input="formUploadFile.document = $event.target.files[0]" class="form-control"
                                type="file" id="formFile">
                            <div v-if="formUploadFile.errors.document">{{
                                formUploadFile.errors.document }}</div>
                        </div>
                        <div class="row justify-center p-3 my-3">
                            <button type="submit" class="btn py-2"
                                style="background-color: #39A900; color: white; "><strong>Crear</strong></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <!-- MODAL associate USERS -->
    <div class="modal fade" id="modalAssociateUser" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" style="width: 350px; height: 600px;">
            <div class="modal-content position-relative p-3" style="max-height: 400px;">
                <div class="d-flex flex-row justify-center px-3">
                    <h4 class="my-3" style="color: #39A900;"><strong>Asociar Investigador</strong></h4>

                    <button type="button" class="btn-close position-absolute top-1 end-3" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body px-3">
                    <form @submit.prevent="associateUser">
                        <div class="px-4 py-2 mb-3 border-2 rounded-lg">

                            <label class="font-bold pb-2">Investigadores</label>

                            <!-- <div v-for="user in users" :key="user.id">
                                <input class="mr-2" type="checkbox" :id="'checkbox-' + user.id"
                                    v-model="formAssociateUser.usersID" :value="user.id">
                                <label :for="'checkbox-' + user.id">{{ user.name }}</label>
                            </div> -->
                            <div class="h-64">
                                <div v-for="(user, index) in paginatedList" :key="user?.id"
                                    :style="index % 2 != 0 ? 'background-color: #FFFFFF' : 'background-color: #F3F3F3'"
                                    class="px-2 py-1">
                                    <div v-if="user">
                                        <input class="mr-2" type="checkbox" :id="'checkbox-' + user.id"
                                            v-model="formAssociateUser.usersID" :value="user.id">
                                        <label :for="'checkbox-' + user.id">
                                            {{ user.name }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div
                                style="display: flex; flex-direction: row; justify-content: center; user-select: none;">
                                <ul class="pagination cursor-pointer mt-1">
                                    <li class="page-item">
                                        <div class="page-link" aria-label="Previous" @click="decreasePaginatorIndex()">
                                            <span aria-hidden="true" style="color: #39A900;">&laquo;</span>
                                        </div>
                                    </li>
                                    <li v-if="paginatedList.length > 0" class="page-item">
                                        <div class="page-link" @click="getCurrentPageList(1)"
                                            :style="paginatorIndex === 1 ? 'color: #FFFFFF; background-color: #39A900;' : 'color: #39A900; background-color: #FFFFFF;'">
                                            1
                                        </div>
                                    </li>
                                    <li v-if="totalPages > 2" class="page-item" aria-current="page">
                                        <div class="page-link"
                                            :style="paginatorIndex !== 1 && paginatorIndex !== totalPages ? 'color: #FFFFFF; background-color: #39A900;' : 'color: #39A900; background-color: #FFFFFF;'">
                                            {{ paginatorIndex != 1 && paginatorIndex != totalPages ? paginatorIndex :
                                            '...' }}
                                        </div>
                                    </li>
                                    <li v-if="totalPages > 1" class="page-item" aria-current="page">
                                        <div class="page-link" @click="getCurrentPageList(totalPages)"
                                            :style="paginatorIndex === totalPages ? 'color: #FFFFFF; background-color: #39A900;' : 'color: #39A900; background-color: #FFFFFF;'">
                                            {{ totalPages }}
                                        </div>
                                    </li>
                                    <li class="page-item">
                                        <div class="page-link" aria-label="Next" @click="incrementPaginatorIndex()">
                                            <span aria-hidden="true" style="color: #39A900;">&raquo;</span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="row justify-center p-3 my-3">
                            <button type="submit" class="btn py-2"
                                style="background-color: #39A900; color: white; "><strong>Asociar /
                                    Desasociar</strong></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>




</template>

<script setup>
import ProjectCard from '@/Pages/Projects/Components/CardProject.vue';
import CardDocumentDetails from '@/Pages/Projects/Project/Components/CardDocumentDetails.vue';
import { CustomAlertsService } from '@/services/customAlerts';
import { Link, useForm, usePage } from '@inertiajs/inertia-vue3'
import { ref, onMounted, onBeforeMount } from 'vue';
import Icon from '@/Shared/Icon.vue';
import { Constants } from '@/core/appFunctions';

const props = defineProps({
    currentYear: { type: String, required: true },
    project: { type: Object, required: true },
    visualizationsRole: { type: Array, required: true },
    investigators: { type: Array, required: true },
    collaborators: { type: Array, required: true }
})

const users = ref([])

const formAssociateUser = useForm({
    usersID: [],
    role: ''
})

const formUploadFile = useForm({
    document: null,
    name: null,
    visualizationRoleSelected: null
})

const checkedUploadFile = ref(null)
const checkedCreateFolder = ref(null)

const isAssociatedUser = ref(null);

const authUser = usePage().props.value.auth.user;

const nameRoleVisualization = {
    "private": "Privado",
    "public": "Público",
    "general-public": "Publico en general"
}

let role = ""

//PAGINATION
const paginatedList = ref([])
const totalPages = ref(0);
const paginatorIndex = ref(1);
const pageElements = 8;

onBeforeMount(() => {
    totalPages.value = Math.ceil(props.investigators.length / pageElements);
    getCurrentPageList(1);
});

const getCurrentPageList = (index) => {
    if (index != paginatorIndex.value) {
        paginatorIndex.value = index;
    }
    paginatedList.value = [];
    let indexBase = (index * pageElements) - pageElements;
    let indexEnd = index * pageElements;

    for (let i = indexBase; i < indexEnd; i++) {
        if (props.investigators[i]) {
            paginatedList.value.push(props.investigators[i]);
        }
    }
}

const incrementPaginatorIndex = () => {
    if (paginatorIndex.value < totalPages.value) {
        paginatorIndex.value++;
        console.log(paginatorIndex.value)
        getCurrentPageList(paginatorIndex.value);
    }
}

const decreasePaginatorIndex = () => {
    if (paginatorIndex.value > 1) {
        paginatorIndex.value--;
        console.log(paginatorIndex.value)
        getCurrentPageList(paginatorIndex.value);
    }
}
//END PAGINATION

onMounted(() => {
    isAssociatedUser.value = verifiyAssociatedUser()
})


const changeInputCheck = (e) => {
    const inputsFile = document.getElementsByClassName("inputs-file")
    const inputsFolder = document.getElementsByClassName("inputs-folder")
    if (e.target.value == "file") {
        changeDisplayCheckBox(inputsFile, "block")
        changeDisplayCheckBox(inputsFolder, "none")
    } else {
        changeDisplayCheckBox(inputsFolder, "block")
        changeDisplayCheckBox(inputsFile, "none")
    }
}

const changeTypeUserToInvestigator = () => {
    formAssociateUser.usersID = []
    users.value = props.investigators
    props.project.users.forEach((user) => {
        if (user.role_id == Constants.INVESTIGATOR_ID) {
            formAssociateUser.usersID.push(user.id)
        }
    })
    role = Constants.INVESTIGATOR
}

const changeTypeUserToCollaborator = () => {
    formAssociateUser.usersID = []
    users.value = props.collaborators
    props.project.users.forEach((user) => {
        if (user.role_id == Constants.COLLABORATOR_ID) {
            formAssociateUser.usersID.push(user.id)
        }
    })
    role = Constants.COLLABORATOR
}

const changeDisplayCheckBox = (inputs, display) => {
    Array.from(inputs).forEach(function (element) {
        element.style.display = display;
    });
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


const upload = () => {

    formUploadFile.post(route(checkedCreateFolder.value.checked ? "folder.upload" : "document.upload", {
        "projectID": props.project.id,
        "validityYear": props.currentYear
    }), {
        onSuccess: () => {
            showSuccessMessage()
            closeModal()
        },
        onError: (e) => {
            console.log(e);
            CustomAlertsService.generalAlert({
                title: 'Error',
                text: 'Ha ocurrido un error al subir el archivo',
                icon: "error",
                isToast: true,
            })
        }
    })
}

const associateUser = () => {
    console.log(formAssociateUser.usersID);
    formAssociateUser.role = role
    formAssociateUser.post(route("project.associated.users", {
        "projectID": props.project.id,
    }), {
        onSuccess: () => {
            showSuccessMessage()
            closeModalAssociateUsers()
        },
        onError: (e) => {
            console.log(e);
            CustomAlertsService.generalAlert({
                title: 'Error',
                text: 'Ha ocurrido un error al asociar el/los usuario/s',
                icon: "error",
                isToast: true,
            })
        }
    })
}

const showSuccessMessage = () => {
    const flashMessage = usePage().props.value.flash.message

    if (flashMessage) {
        CustomAlertsService.successConfirmAlert({
            title: flashMessage,
        })
    } else {
        CustomAlertsService.generalAlert({
            title: 'Error',
            text: usePage().props.value.flash.errorMessage,
            icon: "error",
            isToast: true,
        })
    }
}

const closeModal = () => {
    const modal = document.getElementById("modalNewDocument")
    const modalBootstrap = bootstrap.Modal.getInstance(modal)
    modalBootstrap.hide()
    formUploadFile.reset()
}

const closeModalAssociateUsers = () => {
    const modal = document.getElementById("modalAssociateUser")
    const modalBootstrap = bootstrap.Modal.getInstance(modal)
    modalBootstrap.hide()
    formAssociateUser.reset()
}

</script>

<style scoped>
.folder {
    display: flex;
    flex-direction: column;
    align-items: center;
    max-width: 300px;
}
</style>