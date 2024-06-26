<template>
    <div class="w-full">
        <!-- Buscador -->
        <div class="py-3">
            <div class="ml-5 flex justify-start items-center">
                <Link class="mr-3" method="get" href="/">
                <Icon name="back" />
                </Link>

                <div class="text-2xl font-bold text-color-gray">
                    {{  `Recursos/${props.folder.name}/` }}
                </div>
            </div>
        </div>


        <div class="px-5 pt-1 row row-cols-2 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 row-cols-xxl-5 g-5">

            <button v-if="authUser != null && (authUser?.role.name == 'admin')" class="col" data-bs-toggle="modal" data-bs-target="#modalNewDocument">
                <div class="folder border-3 rounded-4 py-3 bg-white">
                    <svg xmlns="http://www.w3.org/2000/svg" height="84px" viewBox="0 -960 960 960" width="84px"
                        fill="#000000">
                        <path
                            d="M453-280h60v-166h167v-60H513v-174h-60v174H280v60h173v166Zm27.27 200q-82.74 0-155.5-31.5Q252-143 197.5-197.5t-86-127.34Q80-397.68 80-480.5t31.5-155.66Q143-709 197.5-763t127.34-85.5Q397.68-880 480.5-880t155.66 31.5Q709-817 763-763t85.5 127Q880-563 880-480.27q0 82.74-31.5 155.5Q817-252 763-197.68q-54 54.31-127 86Q563-80 480.27-80Z" />
                    </svg>
                    <h6 class="py-1 text-gray-400"><strong>Nuevo</strong></h6>
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


            <div v-for="folder in props.childrenFolders">
                <ProjectCard :folder="folder" :is-shared-resource="true"/>
            </div>
            <div v-for="document in props.folder.documents">
                <CardDocumentDetails :document="document"  :folderID="props.folder.id"/>
            </div>
        </div>
    </div>

    <!-- MODAL NEW FILE-FOLDER -->
    



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
})
const authUser = usePage().props.value.auth.user;

console.log(props.folder);
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