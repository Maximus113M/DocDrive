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

            <button v-if="authUser != null && (authUser?.role.name == 'admin'
                || (isAssociatedUser))" class="col" data-bs-toggle="modal" data-bs-target="#modalSaveProject">
                <div class="folder border-3 rounded-4 py-3 bg-white">
                    <svg xmlns="http://www.w3.org/2000/svg" height="84px" viewBox="0 -960 960 960" width="84px"
                        fill="#000000">
                        <path
                            d="M453-280h60v-166h167v-60H513v-174h-60v174H280v60h173v166Zm27.27 200q-82.74 0-155.5-31.5Q252-143 197.5-197.5t-86-127.34Q80-397.68 80-480.5t31.5-155.66Q143-709 197.5-763t127.34-85.5Q397.68-880 480.5-880t155.66 31.5Q709-817 763-763t85.5 127Q880-563 880-480.27q0 82.74-31.5 155.5Q817-252 763-197.68q-54 54.31-127 86Q563-80 480.27-80Z" />
                    </svg>
                    <h6 class="py-1 text-gray-400"><strong>Nuevo</strong></h6>
                </div>
            </button>

            <button v-if="authUser != null && (authUser?.role.name == 'admin'
                || (isAssociatedUser))" class="col" data-bs-toggle="modal" data-bs-target="#modalSaveProject">
                <div class="folder border-3 rounded-4 py-3 bg-white">
                    <svg xmlns="http://www.w3.org/2000/svg" height="84px" viewBox="0 -960 960 960" width="84px"
                        fill="#39A900">
                        <path
                            d="M453-280h60v-166h167v-60H513v-174h-60v174H280v60h173v166Zm27.27 200q-82.74 0-155.5-31.5Q252-143 197.5-197.5t-86-127.34Q80-397.68 80-480.5t31.5-155.66Q143-709 197.5-763t127.34-85.5Q397.68-880 480.5-880t155.66 31.5Q709-817 763-763t85.5 127Q880-563 880-480.27q0 82.74-31.5 155.5Q817-252 763-197.68q-54 54.31-127 86Q563-80 480.27-80Z" />
                    </svg>
                    <h6 class="py-1 text-gray-400"><strong>Asociar Investigador</strong></h6>
                </div>
            </button>

            <button v-if="authUser != null && (authUser?.role.name == 'admin'
                || (isAssociatedUser))" class="col" data-bs-toggle="modal" data-bs-target="#modalSaveProject">
                <div class="folder border-3 rounded-4 py-3 bg-white">
                    <svg xmlns="http://www.w3.org/2000/svg" height="84px" viewBox="0 -960 960 960" width="84px"
                        fill="#FF6624">
                        <path
                            d="M453-280h60v-166h167v-60H513v-174h-60v174H280v60h173v166Zm27.27 200q-82.74 0-155.5-31.5Q252-143 197.5-197.5t-86-127.34Q80-397.68 80-480.5t31.5-155.66Q143-709 197.5-763t127.34-85.5Q397.68-880 480.5-880t155.66 31.5Q709-817 763-763t85.5 127Q880-563 880-480.27q0 82.74-31.5 155.5Q817-252 763-197.68q-54 54.31-127 86Q563-80 480.27-80Z" />
                    </svg>
                    <h6 class="py-1 text-gray-400"><strong>Asociar Colaborador</strong></h6>
                </div>
            </button>

            <div v-for="folder in props.project.folder">
                <ProjectCard :project="project" :current-year="currentYear" />
            </div>
            <div v-for="document in props.project.documents">
                <CardDocumentDetails :project="project" :current-year="currentYear" />
            </div>
        </div>
    </div>

    <!-- MODAL NEW FILE-FOLDER -->





    <!-- MODAL associate USERS -->
    






</template>

<script setup>
import ProjectCard from '@/Pages/Projects/Components/CardProject.vue';
import CardDocumentDetails from '@/Pages/Projects/Project/Components/CardDocumentDetails.vue';
import { CustomAlertsService } from '@/services/customAlerts';
import { Link, useForm, usePage } from '@inertiajs/inertia-vue3'
import { ref, onMounted } from 'vue';
import Icon from '@/Shared/Icon.vue';

const props = defineProps({
    currentYear: { type: String, required: true },
    project: { type: Object, required: true }
})

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
    "private": "Privado",
    "public": "Público",
    "general-public": "Publico en general"
}

const isAssociatedUser = ref(null);

const authUser = usePage().props.value.auth.user;

onMounted(() => {
    isAssociatedUser.value = verifiyAssociatedUser()
})

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

const modal = ref(null)

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
    CustomAlertsService.successConfirmAlert({
        title: usePage().props.value.flash.message,
    })
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