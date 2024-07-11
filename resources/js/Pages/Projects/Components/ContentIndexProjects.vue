<template>
    <div class="w-full p-5 relative">
        <!-- Buscador -->
        <div class="absolute left-0 top-5">
            <div class="flex justify-between items-center">
                <Link class="ml-5 mr-3" method="get" :href="route('validity.index')">
                <Icon name="back" />
                </Link>

                <div class="text-2xl font-bold text-color-gray">
                    {{ `Vigencias/${currentYear}` }}
                </div>
            </div>
        </div>

        <div class="pt-3 row row-cols-2 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 row-cols-xxl-5 g-5">

            <button v-if="role == 'admin'" class="col" data-bs-toggle="modal" data-bs-target="#modalSaveProject">
                <div class="folder border-3 rounded-4 py-3 bg-white" style="box-shadow: 5px 5px 10px 2px rgba(0, 0, 0, 0.06);">
                    <svg xmlns="http://www.w3.org/2000/svg" height="84px" viewBox="0 -960 960 960" width="84px"
                        fill="#000000">
                        <path
                            d="M453-280h60v-166h167v-60H513v-174h-60v174H280v60h173v166Zm27.27 200q-82.74 0-155.5-31.5Q252-143 197.5-197.5t-86-127.34Q80-397.68 80-480.5t31.5-155.66Q143-709 197.5-763t127.34-85.5Q397.68-880 480.5-880t155.66 31.5Q709-817 763-763t85.5 127Q880-563 880-480.27q0 82.74-31.5 155.5Q817-252 763-197.68q-54 54.31-127 86Q563-80 480.27-80Z" />
                    </svg>
                    <h6 class="py-1 text-gray-400">
                        <strong>Nuevo Proyecto</strong>
                    </h6>
                </div>
            </button>

            <div v-for="project in projects">
                <ProjectCard :visualizationsRole="visualizationsRole" :project="project" :current-year="currentYear" />
            </div>
        </div>
    </div>


    <!-- Modal Create Project-->
    <div ref="modal" class="modal fade" id="modalSaveProject" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content position-relative p-3" style="">
                <div class="flex-row justify-center px-3 border-b-2">
                    <h4 class="my-2" style="color: #39A900;">
                        <strong> Nuevo Proyecto </strong>
                    </h4>

                    <button type="button" class="btn-close position-absolute top-2 end-3" @click="closeModal()"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body px-2">
                    <form @submit.prevent="saveProject">
                        <div class="row">
                            <div class="col-12 col-lg-8">
                                <div class="row">
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-6 mb-3">
                                        <label for="name" class="font-bold">Nombre</label>
                                        <input v-model="form.name" type="text" class="form-control" id="name">
                                        <div v-if="form.errors.name" class="text-center text-red-400">
                                            {{ AppFunctions.getErrorTranslate(AppFunctions.Errors.Field) }}</div>
                                    </div>

                                    <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3 mb-3">
                                        <label for="startDate" class="font-bold">Fecha Inicio</label>
                                        <input v-model="form.startDate" type="date" class="form-control" id="startDate">
                                        <div v-if="form.errors.startDate" class="text-center text-red-400">{{
                                            AppFunctions.getErrorTranslate(AppFunctions.Errors.startDate) }}</div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3 mb-3">
                                        <label for="endDate" class="font-bold">Fecha Fin</label>
                                        <input v-model="form.endDate" type="date" class="form-control" id="endDate">
                                        <div v-if="form.errors.endDate" class="text-center text-red-400">
                                            {{ AppFunctions.getErrorTranslate(AppFunctions.Errors.endDate) }}
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-9 mb-3">
                                        <label for="description" class="font-bold">Descripción</label>
                                        <textarea v-model="form.description" type="text-area"
                                            class="form-control lg:h-48 max-h-48" id="description" />
                                        <div v-if="form.errors.description" class="text-center text-red-400">
                                            {{ AppFunctions.getErrorTranslate(AppFunctions.Errors.Field) }}
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-3 mb-3">
                                        <label class="font-bold">Visualización</label>
                                        <br>
                                        <select v-model="form.visualizationRoleSelected" class="form-select">
                                            <option v-for="vRole in visualizationsRole" :value="vRole.id"
                                                :key="vRole.id">{{
                                                    nameRoleVisualization[vRole.name] }}</option>
                                        </select>
                                        <div v-if="form.errors.visualizationRoleSelected"
                                            class="text-center text-red-400">
                                            {{ AppFunctions.getErrorTranslate(AppFunctions.Errors.Field) }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-lg-4">
                                <div class="px-3 py-2 mb-3 border-2 rounded-lg" style="height: 350px;">
                                    <div class="text-center">
                                        <label class="font-bold pb-2">Asociar Investigadores</label>
                                    </div>
                                    <div class="h-64">
                                        <div v-for="(investigator, index) in paginatedList" :key="investigator?.id"
                                            :style="index % 2 != 0 ? 'background-color: #FFFFFF' : 'background-color: #F3F3F3'"
                                            class="px-2 py-1">
                                            <div v-if="investigator">
                                                <input class="mr-2" type="checkbox" :id="'checkbox-' + investigator.id"
                                                    v-model="form.investigatorsID" :value="investigator.id">
                                                <label :for="'checkbox-' + investigator.id">{{ investigator.name
                                                    }}</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div
                                        style="display: flex; flex-direction: row; justify-content: center; user-select: none;">
                                        <ul class="pagination cursor-pointer mt-1">
                                            <li class="page-item">
                                                <div class="page-link" aria-label="Previous"
                                                    @click="decreasePaginatorIndex()">
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
                                                    {{ paginatorIndex != 1 && paginatorIndex != totalPages? paginatorIndex : '...' }}
                                                </div>
                                            </li>
                                            <li v-if="totalPages > 1" class="page-item" aria-current="page">
                                                <div class="page-link" @click="getCurrentPageList(totalPages)"
                                                    :style="paginatorIndex === totalPages ? 'color: #FFFFFF; background-color: #39A900;' : 'color: #39A900; background-color: #FFFFFF;'">
                                                    {{ totalPages }}
                                                </div>
                                            </li>
                                            <li class="page-item">
                                                <div class="page-link" aria-label="Next"
                                                    @click="incrementPaginatorIndex()">
                                                    <span aria-hidden="true" style="color: #39A900;">&raquo;</span>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div v-if="form.errors.investigatorsID" class="text-center text-red-400">{{
                                    AppFunctions.getErrorTranslate(AppFunctions.Errors.Investigator) }}</div>
                            </div>
                            <!-- 
                        <div class="mb-3">
                            <label for="target" class="font-bold">Objetivos</label>
                            <input v-model="form.target" type="text" class="form-control" id="target">
                            <div v-if="form.errors.target">{{ form.errors.target }}</div>
                        </div>
                        -->
                            <div class="col-12 row justify-center mt-2">
                                <button type="submit" class="btn py-2"
                                    style="background-color: #39A900; color: white; max-width: 400px;">
                                    <strong>Crear</strong>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</template>

<script setup>
import ProjectCard from '@/Pages/Projects/Components/CardProject.vue';
import { CustomAlertsService } from '@/services/customAlerts';
import { Link, useForm, usePage } from '@inertiajs/inertia-vue3'
import { ref, onBeforeMount, onUpdated } from 'vue';
import Icon from '@/Shared/Icon.vue';
import { AppFunctions } from '@/core/appFunctions';

const props = defineProps({
    projects: Object,
    investigators: { type: Array, required: true },
    role: String,
    validityID: Number,
    visualizationsRole: Object,
    currentYear: String,
})
const now = new Date()
const month = (now.getMonth() + 1) > 9 ? now.getMonth() + 1 : "0".concat(now.getMonth() + 1)
const form = useForm({
    name: null,
    startDate: props.currentYear.concat(`-${month}-${now.getDate()}`),
    endDate: props.currentYear.concat(`-${month}-${now.getDate()}`),
    description: null,
    investigatorsID: [],
    validityID: null,
    visualizationRoleSelected: null
    //target: null,
})

const nameRoleVisualization = {
    "private": "Privado",
    "public": "Público",
    "general-public": "Publico en general"
}

const modal = ref(null)


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

const saveProject = () => {
    form.validityID = props.validityID
    form.post(route('project.store'), {
        onSuccess: () => {
            closeModal()
            showSuccessMessage()
        }
    })
}

const showSuccessMessage = () => {
    CustomAlertsService.successConfirmAlert({
        title: usePage().props.value.flash.message,
    })
}

const closeModal = () => {
    form.reset();
    form.clearErrors();
    const modalBootstrap = bootstrap.Modal.getInstance(modal.value)
    modalBootstrap?.hide()
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