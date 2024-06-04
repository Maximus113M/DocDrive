<script setup>
import { Link, useForm, usePage } from '@inertiajs/inertia-vue3';
import FoldersDropdown from '@/Shared/FoldersDropdown.vue';
import BreezeDropdownLink from '@/Components/DropdownLink.vue';
import Icon from '@/Shared/Icon.vue';
import { onMounted, ref } from 'vue';

const { project } = defineProps(['project']);


const form = useForm({
    name: project.name,
    description: project.description,
    startDate: project.startDate,
    endDate: project.endDate,
    target: project.target,
})

const onClicks = (event) => {
    event.preventDefault();
}

const isAssociatedUser = ref(null);

const authUser = usePage().props.value.auth.user ?? null;

const verifiyAssociatedUser = () => {
    if (authUser == null) {
        return false;
    }
    for (let index = 0; index < project.users.length; index++) {
        const user = project.users[index];
        if (user.id == authUser.id) {
            return true;
        } else {
            continue;
        }
    }
    return false;
}

const openModalUpdate = () => {
    const modal = document.getElementById("modal-update-project-" + project.id)
    const modalBootstrap = new bootstrap.Modal(modal)
    modalBootstrap.show()
}

const updateProject = () => {
    form.put(route("project.update", {"projectID" : project.id}))
}

onMounted(() => {
    isAssociatedUser.value = verifiyAssociatedUser()
})

</script>

<template>
    <!-- PROJECT DESIGN -->
    <div class="col position-relative" style="max-width: 300px;">

        <Link class="text-decoration-none">
        <div class="d-flex flex-column align-items-center border-3 rounded-4 py-1 bg-white">
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
                            data-bs-toggle="modal" data-bs-target="#modal" @click="openModalUpdate">
                            Editar
                        </button>
                        <button
                            class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                            data-bs-toggle="modal" data-bs-target="#modal" @click="openModalDelete">
                            Eliminar
                        </button>
                    </template>
                </FoldersDropdown>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" height="80px" viewBox="0 -960 960 960" width="80px" fill="#FEC63D">
                <path
                    d="M140-160q-24 0-42-18.5T80-220v-520q0-23 18-41.5t42-18.5h281l60 60h339q23 0 41.5 18.5T880-680v460q0 23-18.5 41.5T820-160H140Z" />
            </svg>



            <div class="validity-font">
                Proyecto
            </div>
            <h5 class="text-black"><strong>{{ project.name }}</strong></h5>
            <p v-if="project.isIncomplete && isAssociatedUser"
                class="text-xs px-2 border-black border-1 bg-yellow-500 color-black text-center">¡Datos del proyecto
                incompleto!</p>
        </div>
        </Link>
    </div>
    <!-- PROJECT DESIGN -->




    <!-- MODAL EDITAR-->
    <div class="modal fade" :id="`modal-update-project-${project.id}`" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="width: 350px; height: 600px;">
            <div class="modal-content position-relative p-3" style="max-height: 800px;">
                <div class="d-flex flex-row justify-center px-3">
                    <h4 class="my-3" style="color: #39A900;"><strong>Actualizar proyecto</strong></h4>

                    <button type="button" class="btn-close position-absolute top-1 end-3" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body px-3">
                    <form @submit.prevent="updateProject">
                        <div class="mb-3">
                            <label for="name" class="form-label">Ingrese el nombre:</label>
                            <input v-model="form.name" type="text" class="form-control" id="name">
                            <div v-if="form.errors.name">{{ form.errors.name }}</div>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Ingrese la descripción:</label>
                            <input v-model="form.description" type="text" class="form-control" id="description">
                            <div v-if="form.errors.description">{{ form.errors.description }}</div>
                        </div>
                        <div class="mb-3">
                            <label for="startDate" class="form-label">Ingrese la fecha de inicio:</label>
                            <input v-model="form.startDate" type="date" class="form-control" id="startDate">
                            <div v-if="form.errors.startDate">{{ form.errors.startDate }}</div>
                        </div>
                        <div class="mb-3">
                            <label for="endDate" class="form-label">Ingrese la fecha fin:</label>
                            <input v-model="form.endDate" type="date" class="form-control" id="endDate">
                            <div v-if="form.errors.endDate">{{ form.errors.endDate }}</div>
                        </div>
                        <div class="row justify-center p-3 mt-5">
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
