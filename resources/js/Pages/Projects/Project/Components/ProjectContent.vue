<template>
    <div class="pb-10">
        <!-- Buscador -->
        <div class="flex flex-wrap justify-end py-3 pl-5 pr-3">

            <div class="col-12 col-lg-8 col-xl-9 flex justify-start items-center">
                <Link class="mr-3" method="get" :href="backRoute()">
                <Icon name="back" />
                </Link>

                <div class="text-xl font-bold">
                    {{ !folder ? `Vigencias/${props.currentYear}/${props.project.name}`
                        : `Vigencias/${props.currentYear}/${props.project.name}${folder.documentPath}${folder.name}` }}
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                <div class="flex justify-end pr-3 xl:pr-5">
                    <div class="flex max-w-72 py-2 rounded-2xl border-1 border-gray-300">
                        <Icon name="search" />
                        <input @keyup="search" type="text" style="all: unset" placeholder="Buscar">
                    </div>
                </div>
            </div>
        </div>

        <div v-if="!folder" class="px-5">
            <article class="pt-3 pb-1 px-3 question cursor-pointer mb-2"
                @click="expandedDescription = !expandedDescription">
                <header class="flex justify-between items-center pb-2">
                    <div class="font-semibold text-xl w-full">
                        Descripción
                    </div>

                    <Icon v-show="!expandedDescription" name="arrow-down" />
                    <Icon v-show="expandedDescription" name="arrow-up" />
                </header>

                <div :style="`max-height: ${expandedDescription ? '200px' : '0'}`" class="content overflow-y-scroll">
                    <p :style="`opacity: ${expandedDescription ? '1' : '0'}`" class="mt-2 info">
                        {{ props.project.description?.length > 0 ? props.project.description
                            : 'El proyecto aún no tiene descripción' }}
                    </p>
                </div>
            </article>

            <article class="pt-3 pb-1 px-3 question cursor-pointer mb-3" @click="expandedTargets = !expandedTargets">
                <header class="flex justify-between items-center pb-2">
                    <div class="font-semibold text-xl w-full">
                        Objetivos
                    </div>

                    <Icon v-show="!expandedTargets" name="arrow-down" />
                    <Icon v-show="expandedTargets" name="arrow-up" />
                </header>

                <div :style="`max-height: ${expandedTargets ? '200px' : '0'}`" class="content overflow-y-scroll">
                    <p :style="`opacity: ${expandedTargets ? '1' : '0'}`" class="mt-2 info">
                    <div v-for="(target, index) of targetList">
                        <div class="flex">
                            <strong class="mr-1">{{ `${index + 1}. ` }}</strong>
                            {{ ` ${target}` }}
                        </div>
                    </div>

                    <div v-if="targetList.length <= 0">
                        El proyecto aún no tiene objetivos
                    </div>

                    </p>
                </div>
            </article>
        </div>

        <div class="w-full px-5 pt-1 row row-cols-2 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 row-cols-xxl-5 g-5">

            <button v-if="authUser != null && (authUser?.role.name == 'admin'
                || (isAssociatedUser))" class="col" data-bs-toggle="modal" data-bs-target="#modalNewDocument"
                @click="resetForms">
                <div class="folder border-3 rounded-4 py-3 bg-white">
                    <svg xmlns="http://www.w3.org/2000/svg" height="84px" viewBox="0 -960 960 960" width="84px"
                        fill="#000000">
                        <path
                            d="M453-280h60v-166h167v-60H513v-174h-60v174H280v60h173v166Zm27.27 200q-82.74 0-155.5-31.5Q252-143 197.5-197.5t-86-127.34Q80-397.68 80-480.5t31.5-155.66Q143-709 197.5-763t127.34-85.5Q397.68-880 480.5-880t155.66 31.5Q709-817 763-763t85.5 127Q880-563 880-480.27q0 82.74-31.5 155.5Q817-252 763-197.68q-54 54.31-127 86Q563-80 480.27-80Z" />
                    </svg>
                    <h6 class="py-1 text-gray-400">
                        <strong>Nuevo</strong>
                    </h6>
                </div>
            </button>

            <button name="investigator" :onclick="changeTypeUserToInvestigator" v-if="authUser != null && !props.folder && (authUser?.role.name == 'admin'
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

            <button name="collaborator" :onclick="changeTypeUserToCollaborator" v-if="authUser != null && !props.folder && (authUser?.role.name == 'admin'
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

            <div v-for="folder in folderList" :key="folder.id">
                <ProjectCard :folder="folder" :project="project" :current-year="currentYear"
                    :visualizations-role="props.visualizationsRole" :is-folder="true" />
            </div>
            <div v-for="document in documentList" :key="document.id">
                <CardDocumentDetails :visualizations-role="props.visualizationsRole" :currentYear="Number(currentYear)"
                    :document="document" :project="project" :documentCategories="documentCategories" />
            </div>
        </div>
    </div>

    <!-- MODAL NEW FILE-FOLDER -->
    <div class="modal fade" id="modalNewDocument" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" style="width: 350px; height: 600px;">
            <div class="modal-content position-relative p-3" style="max-height: 600px;">
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
                            <div class="form-check mr-4">
                                <input :onclick="changeInputCheck" ref="checkedUploadLink" class="form-check-input"
                                    type="radio" name="file" id="link" value="link">
                                <label class="form-check-label" for="link">
                                    Link
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
                        <div class="mb-3">
                            <label class="font-bold">Nombre</label>
                            <br>
                            <input v-model="formUploadFile.name" class="form-control" type="text" placeholder="Nombre">
                            <div v-if="formUploadFile.errors.name" class="text-red-500">
                                {{ formUploadFile.errors.name }}
                            </div>
                        </div>
                        <div class="mb-3 inputs-file">
                            <div class="font-bold">Categorías</div>
                            <select v-model="formUploadFile.category" class="form-select">
                                <option v-for="category in props.documentCategories" :value="category.id"
                                    :key="category.id">{{
                                        category.name }}</option>
                            </select>
                        </div>
                        <div class="mb-3 inputs-file">
                            <label for="formFile" class="font-bold form-label">Seleccionar archivo</label>
                            <input @input="formUploadFile.document = $event.target.files[0]" class="form-control"
                                type="file" id="formFile">
                            <div v-if="formUploadFile.errors.document" class="text-red-500">
                                {{ AppFunctions.getErrorTranslate(AppFunctions.Errors.File) }}
                            </div>
                        </div>
                        <div class="mb-3 inputs-link hidden">
                            <label for="link" class="font-bold form-label">Link</label>
                            <input v-model="formUploadFile.link" class="form-control" type="url" id="link"
                                placeholder="ingrese el link">
                            <div v-if="formUploadFile.errors.link" class="text-red-500">
                                {{ AppFunctions.getErrorTranslate(AppFunctions.Errors.Field) }}
                            </div>
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
        <div class="modal-dialog" style="width: 420px; height: 660px;">
            <div class="modal-content position-relative p-3" style="max-height: 660px;">
                <div class="d-flex flex-row justify-center px-3">
                    <h4 class="my-3" :style="`color: ${role == 'investigator' ? '#39A900' : '#FF6624'}`">
                        <strong>
                            Asociar {{ role == Constants.COLLABORATOR ? 'Colaborador' : 'Investigador' }}
                        </strong>
                    </h4>
                    <button type="button" class="btn-close position-absolute top-1 end-3" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body px-3">
                    <form @submit.prevent="associateUser">
                        <div class="px-4 py-2 mb-3 border-2 rounded-lg">

                            <div class="flex justify-center mb-3 mt-1">
                                <div class="col-12 flex py-2 rounded-2xl border-1 border-gray-300">
                                    <Icon name="search" />
                                    <input @keyup="searchUsers" type="text" style="all: unset" placeholder="Buscar">
                                </div>
                            </div>
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
                            <div style="display: flex; flex-direction: row; justify-content: center; user-select: none;"
                                class="mt-2">
                                <ul class="pagination cursor-pointer mt-1">
                                    <li class="page-item">
                                        <div class="page-link" aria-label="Previous" @click="decreasePaginatorIndex()">
                                            <span aria-hidden="true"
                                                :style="`color: ${role == 'investigator' ? '#39A900' : '#FF6624'};`">&laquo;</span>
                                        </div>
                                    </li>
                                    <li v-if="paginatedList.length > 0" class="page-item">
                                        <div class="page-link" @click="getCurrentPageList(1)"
                                            :style="paginatorIndex === 1 ? currentColor : defaultColor">
                                            1
                                        </div>
                                    </li>
                                    <li v-if="totalPages > 2" class="page-item" aria-current="page">
                                        <div class="page-link"
                                            :style="paginatorIndex !== 1 && paginatorIndex !== totalPages ? currentColor : defaultColor">
                                            {{ paginatorIndex != 1 && paginatorIndex != totalPages ? paginatorIndex :
                                                '...' }}
                                        </div>
                                    </li>
                                    <li v-if="totalPages > 1" class="page-item" aria-current="page">
                                        <div class="page-link" @click="getCurrentPageList(totalPages)"
                                            :style="paginatorIndex === totalPages ? currentColor : defaultColor">
                                            {{ totalPages }}
                                        </div>
                                    </li>
                                    <li class="page-item">
                                        <div class="page-link" aria-label="Next" @click="incrementPaginatorIndex()">
                                            <span aria-hidden="true"
                                                :style="`color: ${role == 'investigator' ? '#39A900' : '#FF6624'};`">&raquo;</span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="row justify-center p-3 my-3">
                            <button type="submit" class="text-white btn py-2"
                                :style="`background-color: ${role == 'investigator' ? '#39A900' : '#FF6624'}`">
                                <strong>Asociar/Desasociar</strong>
                            </button>
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
import { ref, onMounted, watch } from 'vue';
import Icon from '@/Shared/Icon.vue';
import { AppFunctions, Constants } from '@/core/appFunctions';

const props = defineProps({
    folder: { type: Object, required: false },
    currentYear: { type: String, required: true },
    project: { type: Object, required: true },
    visualizationsRole: { type: Array, required: true },
    investigators: { type: Array, required: true },
    collaborators: { type: Array, required: true },
    documentCategories: { type: Array, required: true },
});

const users = ref([]);

const visualizationsRole = ref([]);
const formAssociateUser = useForm({
    usersID: [],
    role: ''
});

const formUploadFile = useForm({
    document: null,
    name: null,
    visualizationRoleSelected: null,
    folder_id: props.folder != null ? props.folder.id : null,
    link: null,
    category: null,
});

const checkedUploadFile = ref(null);
const checkedCreateFolder = ref(null);
const checkedUploadLink = ref(null);


const isAssociatedUser = ref(null);
const authUser = usePage().props.value.auth.user;

const nameRoleVisualization = {
    "private": "Privado",
    "public": "Público",
    "general-public": "Publico en general"
}

let role = "";
const currentColor = ref('');
const defaultColor = ref('');

const folderList = ref([...Object.values(props.project.folders)]);
const documentList = ref([...Object.values(props.project.documents)]);

//PAGINATION
const paginatedList = ref([]);
const totalPages = ref(0);
const paginatorIndex = ref(1);
const pageElements = 8;

const targetList = ref([]);

onMounted(() => {
    console.log("#########################")
    console.log(props.project.folders)
    isAssociatedUser.value = verifiyAssociatedUser();
    validateVisualizationRole();
    const targets = JSON.parse(props.project.target);
    for (let key in targets) {
        targetList.value.push(targets[key]);
    }

    const miModal = document.getElementById("modalNewDocument");
    if (miModal) {
        miModal.addEventListener('hidden.bs.modal', function () {
            closeModal();
        });
    }
});

watch(() => props.project, (_) => {
    console.log('Changes Dectected, Reload');
    folderList.value.length = 0;
    documentList.value.length = 0
    setTimeout(() => {
        folderList.value = [...Object.values(props.project.folders)];
        documentList.value = [...props.project.documents];
    }, 100);
    
});


const backRoute = () => {
    if (props.folder && props.folder.father_id) {
        return `/${props.currentYear}/projects/${props.project.id}/folders/${props.folder.father_id}`
    } else if (props.folder) {
        return `/${props.currentYear}/projects/${props.project.id}`
    } else {
        return `/${props.currentYear}/projects`
    }
}

//PAGINATION
const getCurrentPageList = (index) => {
    if (index != paginatorIndex.value) {
        paginatorIndex.value = index;
    }
    paginatedList.value = [];
    let indexBase = (index * pageElements) - pageElements;
    let indexEnd = index * pageElements;

    for (let i = indexBase; i < indexEnd; i++) {
        if (users.value[i]) {
            paginatedList.value.push(users.value[i]);
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

console.log(documentList.value);

//GENERAL FILTER  
const search = (e) => {
    const inputSearch = e.target.value;
    if (e.target.value.length === 0) {
        console.log('Search Empty')
        folderList.value = [...props.project.folders];
        documentList.value = [...props.project.documents];
        return;
    }
    folderList.value.length = 0;
    documentList.value.length = 0;

    folderList.value = [...props.project.folders.filter((user) =>
        user.name.toLowerCase().includes(inputSearch.toLowerCase()))];
    documentList.value = [...props.project.documents.filter((user) =>
        user.name.toLowerCase().includes(inputSearch.toLowerCase()))];
    console.log(documentList.value);
}

//FILTER Users  
const searchUsers = (e) => {
    const inputSearch = e.target.value
    let filtered = [];
    if (role === 'investigator') {
        filtered = props.investigators.filter((user) =>
            user.name.toLowerCase().includes(inputSearch.toLowerCase()));
    } else {
        filtered = props.collaborators.filter((user) =>
            user.name.toLowerCase().includes(inputSearch.toLowerCase()));
    }

    if (filtered.length <= pageElements) {
        totalPages.value = 1
    } else {
        totalPages.value = Math.ceil(filtered.length / pageElements);
    }
    let indexBase = (paginatorIndex.value * pageElements) - pageElements;
    let indexEnd = paginatorIndex.value * pageElements;

    if (indexEnd / pageElements > totalPages.value) {
        indexBase = 0
        indexEnd = pageElements
    }
    users.value = filtered
    filtered = filtered.slice(indexBase, indexEnd)
    paginatedList.value = filtered;
}

const validateVisualizationRole = () => {
    let visualization_role_id = props.folder != null ? props.folder.visualization_role_id : props.project.visualization_role_id

    if (visualization_role_id == Constants.PRIVATE_ID) {
        visualizationsRole.value = props.visualizationsRole = props.visualizationsRole.filter((element) => element.id == Constants.PRIVATE_ID)
    } else if (visualization_role_id == Constants.PUBLIC_ID) {
        visualizationsRole.value = props.visualizationsRole.filter((element) => {
            return element.id == Constants.PUBLIC_ID || element.id == Constants.PRIVATE_ID
        })
    } else {
        visualizationsRole.value = props.visualizationsRole
    }
}

const changeInputCheck = (e) => {
    const inputsFile = document.getElementsByClassName("inputs-file")
    const inputsFolder = document.getElementsByClassName("inputs-folder")
    const inputsLink = document.getElementsByClassName("inputs-link")
    if (e.target.value == "file") {
        changeDisplayCheckBox(inputsFile, "block")
        changeDisplayCheckBox(inputsFolder, "none")
        changeDisplayCheckBox(inputsLink, "none")
    } else if (e.target.value == "link") {
        changeDisplayCheckBox(inputsLink, "block")
        changeDisplayCheckBox(inputsFolder, "none")
        changeDisplayCheckBox(inputsFile, "none")
    } else {
        changeDisplayCheckBox(inputsFolder, "block")
        changeDisplayCheckBox(inputsFile, "none")
        changeDisplayCheckBox(inputsLink, "none")
    }
}

const changeTypeUserToInvestigator = () => {
    users.length = 0;
    formAssociateUser.usersID = []
    users.value = [...props.investigators];
    totalPages.value = Math.ceil(users.value.length / pageElements);
    getCurrentPageList(1);
    props.project.users.forEach((user) => {
        if (user.role_id == Constants.INVESTIGATOR_ID) {
            formAssociateUser.usersID.push(user.id)
        }
    })
    role = Constants.INVESTIGATOR
    currentColor.value = 'color: #FFFFFF; background-color: #39A900;';
    defaultColor.value = 'color: #39A900; background-color: #FFFFFF;';
}

const changeTypeUserToCollaborator = () => {
    users.length = 0;
    formAssociateUser.usersID = []
    users.value = [...props.collaborators];
    totalPages.value = Math.ceil(users.value.length / pageElements);
    getCurrentPageList(1);
    props.project.users.forEach((user) => {
        if (user.role_id == Constants.COLLABORATOR_ID) {
            formAssociateUser.usersID.push(user.id)
        }
    })
    role = Constants.COLLABORATOR;
    currentColor.value = 'color: #FFFFFF; background-color: #FF6624;'
    defaultColor.value = 'color: #FF6624; background-color: #FFFFFF;';

}

const changeDisplayCheckBox = (inputs, display) => {
    Array.from(inputs).forEach(function (element) {
        element.style.display = display;
    });
}

const verifiyAssociatedUser = () => {
    if (!authUser || !props.project.users) {
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
    let ruta = null;
    if (checkedUploadFile.value.checked) {
        ruta = "document.upload"
    } else if (checkedUploadLink.value.checked) {
        ruta = "link.add"
    } else {
        ruta = "folder.upload"
    }

    formUploadFile.post(route(ruta, {
        "projectID": props.project.id,
        "validityYear": props.currentYear
    }), {
        onSuccess: () => {
            showSuccessMessage()
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
    closeModal();
}

const associateUser = () => {
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

    const fileInput = document.getElementById("formFile")
    fileInput.value = ""

    formUploadFile.reset()
}

const closeModalAssociateUsers = () => {
    const modal = document.getElementById("modalAssociateUser")
    const modalBootstrap = bootstrap.Modal.getInstance(modal)
    modalBootstrap.hide()
    formAssociateUser.reset()
}

let miModal = document.getElementById("modalNewDocument")
console.log(miModal);


const resetForms = () => {
    formUploadFile.reset();
}



//Accordion
const expandedDescription = ref(false);
const expandedTargets = ref(false);

</script>

<style scoped>
.folder {
    display: flex;
    flex-direction: column;
    align-items: center;
    max-width: 300px;
    box-shadow: 5px 5px 10px 2px rgba(0, 0, 0, 0.06);
}

.question {
    border: 2px solid #e7e3e3;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.09);
}

.content {
    max-height: 0;
    transition: max-height 0.3s ease-out;
}

.info {
    z-index: -1;
    opacity: 0;
    transition: opacity 0.3s ease-out;
}
</style>