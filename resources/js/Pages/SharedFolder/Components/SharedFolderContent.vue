<template>
    <div class="w-full">
        <!-- Buscador -->
        <div class="py-3">
            <div class="ml-5 flex justify-start items-center">
                <Link class="mr-3" method="get" href="/">
                <Icon name="back" />
                </Link>

                <div class="text-2xl font-bold text-color-gray">
                    {{ `Recursos/${props.folder.name}/` }}
                </div>
            </div>
        </div>


        <div class="px-5 pt-1 row row-cols-2 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 row-cols-xxl-5 g-5">

            <button v-if="authUser != null && (authUser?.role.name == 'admin')" class="col" data-bs-toggle="modal"
                data-bs-target="#modalAssociateDocument">
                <div class="folder border-3 rounded-4 py-3 bg-white">
                    <svg xmlns="http://www.w3.org/2000/svg" height="84px" viewBox="0 -960 960 960" width="84px"
                        fill="#000000">
                        <path
                            d="M453-280h60v-166h167v-60H513v-174h-60v174H280v60h173v166Zm27.27 200q-82.74 0-155.5-31.5Q252-143 197.5-197.5t-86-127.34Q80-397.68 80-480.5t31.5-155.66Q143-709 197.5-763t127.34-85.5Q397.68-880 480.5-880t155.66 31.5Q709-817 763-763t85.5 127Q880-563 880-480.27q0 82.74-31.5 155.5Q817-252 763-197.68q-54 54.31-127 86Q563-80 480.27-80Z" />
                    </svg>
                    <h6 class="py-1 text-gray-400"><strong>Asociar Documento</strong></h6>
                </div>
            </button>


            <div v-for="folder in props.childrenFolders">
                <ProjectCard :folder="folder" :is-shared-resource="true" />
            </div>
            <div v-for="document in props.folder.documents">
                <CardDocumentDetails :document="document" :folderID="props.folder.id" />
            </div>
        </div>
    </div>

    <!-- MODAL ASOCIAR DOCUMENTO -->


    <div class="modal fade" id="modalAssociateDocument" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" style="width: 420px; height: 580px;">
            <div class="modal-content position-relative p-3" style="max-height: 580px;">
                <div class="d-flex flex-row justify-center px-3">
                    <h4 class="my-3" style="color: #39A900">
                        <strong>
                            Asociar Documento
                        </strong>
                    </h4>
                    <button type="button" class="btn-close position-absolute top-1 end-3" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body px-3">
                    <form @submit.prevent="associateDocument">
                        <div class="px-4 py-2 mb-3 border-2 rounded-lg">
                            <div class="flex">
                                <label class="font-bold pb-2">
                                    Documentos
                                </label>
                                <input @keyup="search" class="w-50 h-6 absolute right-0" type="text" name="search"
                                    placeholder="Buscar">
                            </div>
                            <div class="h-64">
                                <div v-for="(doc, index) in paginatedList" :key="doc?.id"
                                    :style="index % 2 != 0 ? 'background-color: #FFFFFF' : 'background-color: #F3F3F3'"
                                    class="px-2 py-1">
                                    <div v-if="doc">
                                        <input class="mr-2" type="checkbox" :id="'checkbox-' + doc.id"
                                            v-model="formAssociateDocument.documentsID" :value="doc.id">
                                        <label :for="'checkbox-' + doc.id">
                                            {{ doc.name }}.{{ doc.format }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div
                                style="display: flex; flex-direction: row; justify-content: center; user-select: none;">
                                <ul class="pagination cursor-pointer mt-1">
                                    <li class="page-item">
                                        <div class="page-link" aria-label="Previous" @click="decreasePaginatorIndex()">
                                            <span aria-hidden="true"
                                                :style="`color: ${role == 'investigator' ? '#39A900' : '#FF6624'};`">&laquo;</span>
                                        </div>
                                    </li>
                                    <li v-if="paginatedList.length > 0" class="page-item">
                                        <div class="page-link" @click="getCurrentPageList(1)"
                                            :style="paginatorIndex === 1 ? currentColor : 'color: #39A900; background-color: #FFFFFF;'">
                                            1
                                        </div>
                                    </li>
                                    <li v-if="totalPages > 2" class="page-item" aria-current="page">
                                        <div class="page-link"
                                            :style="paginatorIndex !== 1 && paginatorIndex !== totalPages ? currentColor : 'color: #39A900; background-color: #FFFFFF;'">
                                            {{ paginatorIndex != 1 && paginatorIndex != totalPages ? paginatorIndex :
                                                '...' }}
                                        </div>
                                    </li>
                                    <li v-if="totalPages > 1" class="page-item" aria-current="page">
                                        <div class="page-link" @click="getCurrentPageList(totalPages)"
                                            :style="paginatorIndex === totalPages ? currentColor : 'color: #39A900; background-color: #FFFFFF;'">
                                            {{ totalPages }}
                                        </div>
                                    </li>
                                    <li class="page-item">
                                        <div class="page-link" aria-label="Next" @click="incrementPaginatorIndex()">
                                            <span aria-hidden="true" style="color: #39A900">&raquo;</span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="row justify-center p-3 my-3">
                            <button type="submit" class="text-white btn py-2"
                                style="background-color:#39A900"><strong>Asociar
                                </strong></button>
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
import { AppFunctions, Constants } from '@/core/appFunctions';

const props = defineProps({
    folder: { type: Object, required: false },
    childrenFolders: { type: Array, required: true },
    documents: { type: Array, required: true }
})
const authUser = usePage().props.value.auth.user;

const formAssociateDocument = useForm({
    documentsID: props.folder.documents.map(doc => doc.id),
})

const currentColor = ref('')
const paginatedList = ref([])
const totalPages = ref(0);
const paginatorIndex = ref(1);
const pageElements = 8;
const documents = ref(props.documents)



onBeforeMount(() => {
    totalPages.value = Math.ceil(documents.value.length / pageElements);
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
        if (documents.value[i]) {
            paginatedList.value.push(documents.value[i]);
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



const search = (e) => {
    const inputSearch = e.target.value
    let filtered = props.documents.filter((document) => {
        return document.name.toLowerCase().includes(inputSearch.toLowerCase())
    })
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
    documents.value = filtered
    filtered = filtered.slice(indexBase, indexEnd)
    paginatedList.value = filtered;
}

const associateDocument = () => {
    formAssociateDocument.post(route("shared.document.associated", {
        "folderID": props.folder.id,
    }), {
        onSuccess: () => {
            showSuccessMessage()
            closeModalAssociateDocuments()
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


const closeModalAssociateDocuments = () => {
    const modal = document.getElementById("modalAssociateDocument")
    const modalBootstrap = bootstrap.Modal.getInstance(modal)
    modalBootstrap.hide()
}


</script>

<style scoped>
.folder {
    display: flex;
    flex-direction: column;
    align-items: center;
    max-width: 300px;
    box-shadow: 5px 5px 10px 2px rgba(0, 0, 0, 0.08);
}

.question {
    border: 2px solid #EFEFEF;
    margin-bottom: 1rem;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.content {
    max-height: 0;
    transition: max-height 0.2s ease-out;
}

.info {
    z-index: -1;
    opacity: 0;
    transition: opacity 0.2s ease-out;
}
</style>