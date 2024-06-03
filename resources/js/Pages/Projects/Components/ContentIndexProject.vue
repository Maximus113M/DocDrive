<template>
    <div class="p-5 relative">
        <!-- Buscador -->
        <div class="absolute top-0 left-0 p-3 row">
            <div class="text-2xl font-bold text-color-gray ml-5">
                Vigencias/Proyectos
            </div>
            <div class="">

            </div>
        </div>

        <div class="pt-3 row row-cols-2 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 row-cols-xxl-5 g-5">

            <button v-if="role == 'admin' && isAuthenticated" class="col" data-bs-toggle="modal"
                data-bs-target="#modalSaveProject">
                <div class="folder border-3 rounded-4 py-3 bg-white">
                    <svg xmlns="http://www.w3.org/2000/svg" height="84px" viewBox="0 -960 960 960" width="84px"
                        fill="#000000">
                        <path
                            d="M453-280h60v-166h167v-60H513v-174h-60v174H280v60h173v166Zm27.27 200q-82.74 0-155.5-31.5Q252-143 197.5-197.5t-86-127.34Q80-397.68 80-480.5t31.5-155.66Q143-709 197.5-763t127.34-85.5Q397.68-880 480.5-880t155.66 31.5Q709-817 763-763t85.5 127Q880-563 880-480.27q0 82.74-31.5 155.5Q817-252 763-197.68q-54 54.31-127 86Q563-80 480.27-80Z" />
                    </svg>
                    <h6 class="py-1 text-gray-400"><strong>Nuevo Proyecto</strong></h6>
                </div>
            </button>

            <div v-for="project in projects">
                <Card :project="project"/>
            </div>
        </div>
    </div>


    <!-- Modal -->

    <div ref="modal" class="modal fade" id="modalSaveProject" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content position-relative p-3" style="max-height: 800px;">
                <div class="d-flex flex-row justify-center px-3">
                    <h4 class="my-3" style="color: #39A900;"><strong>{{
                        'Nuevo Proyecto' }} </strong>
                    </h4>

                    <button type="button" class="btn-close position-absolute top-1 end-3" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body px-3">
                    <form @submit.prevent="saveProject">
                        <div class="mb-3">
                            <label for="name" class="font-bold">Nombre</label>
                            <input v-model="form.name" type="text" class="form-control" id="name">
                            <div v-if="form.errors.name">{{ form.errors.name }}</div>
                        </div>
                        <!--
                        <div class="mb-3">
                            <label for="startDate" class="font-bold">Fecha Inicio</label>
                            <input v-model="form.startDate" type="date" class="form-control" id="startDate">
                            <div v-if="form.errors.startDate">{{ form.errors.startDate }}</div>
                        </div>
                        <div class="mb-3">
                            <label for="endDate" class="font-bold">Fecha Fin</label>
                            <input v-model="form.endDate" type="date" class="form-control" id="endDate">
                            <div v-if="form.errors.endDate">{{ form.errors.endDate }}</div>
                        </div>
                        <div class="mb-3">
                            <label for="target" class="font-bold">Objetivos</label>
                            <input v-model="form.target" type="text" class="form-control" id="target">
                            <div v-if="form.errors.target">{{ form.errors.target }}</div>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="font-bold">Descripción</label>
                            <input v-model="form.description" type="text" class="form-control" id="description">
                            <div v-if="form.errors.description">{{ form.errors.description }}</div>
                        </div>
                        -->

                        <div class="mb-3">
                            <label class="font-bold">Asociar Investigadores</label>
                            <div v-for="investigator in investigators" :key="investigator.id">
                                <input class="mr-2" type="checkbox" :id="'checkbox-' + investigator.id"
                                    v-model="form.investigatorsID" :value="investigator.id">
                                <label :for="'checkbox-' + investigator.id">{{ investigator.name }}</label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="font-bold">Elige rol de visualización</label>
                            <br>
                            <select v-model="form.visualizationRoleSelected">
                                <option v-for="vRole in visualizationsRole" :value="vRole.id" :key="vRole.id">{{ nameRoleVisualization[vRole.name] }}</option>
                            </select>
                        </div>

                        <div class="row justify-center p-3 mt-5">
                            <button type="submit" class=" py-2"
                                style="background-color: #39A900; color: white; "><strong>Crear
                                </strong></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


</template>

<script setup>
import Card from '@/Pages/Projects/Components/CardProject.vue';
import { useForm, usePage } from '@inertiajs/inertia-vue3'
import { ref } from 'vue';

const form = useForm({
    name: null,
    //startDate: null,
    //endDate: null,
    //target: null,
    //description: null,
    investigatorsID: [],
    validityID: null,
    visualizationRoleSelected: null
})

const nameRoleVisualization = {
    "private" : "Privado",
    "public" : "Público",
    "general-public" : "Publico en general"
}

const modal = ref(null)

const { investigators, validityID } = defineProps({
    projects: Object,
    investigators: Object,
    isAuthenticated: Boolean,
    role: String,
    validityID: Number,
    visualizationsRole: Object
})

const saveProject = () => {
    form.validityID = validityID
    form.post(route('project.store'), {
        onSuccess: () => {
            closeModal()
            showSuccessMessage()
        }
    })
}

const showSuccessMessage = () => {
    alert(usePage().props.value.flash.message)
}

const closeModal = () => {
    const modalBootstrap = bootstrap.Modal.getInstance(modal.value)
    modalBootstrap.hide()
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