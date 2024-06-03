<script setup>
import { Link, usePage } from '@inertiajs/inertia-vue3';
import FoldersDropdown from '@/Shared/FoldersDropdown.vue';
import BreezeDropdownLink from '@/Components/DropdownLink.vue';
import Icon from '@/Shared/Icon.vue';
import { onMounted, ref } from 'vue';

const { project } = defineProps(['project']);

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

onMounted(() => {
    isAssociatedUser.value = verifiyAssociatedUser()
})

</script>

<template>
    <!-- PROJECT DESIGN -->
    <div class="col position-relative" style="max-width: 300px;">
      
        <Link class="text-decoration-none">
        <div class="d-flex flex-column align-items-center border-3 rounded-4 py-1 bg-white">
            <div v-if="authUser != null && ( authUser.role.name == 'admin'
                || (authUser.role.name == 'investigator' && isAssociatedUser) )" class="position-absolute top-1 end-1" @click="onClicks">
                <FoldersDropdown align="right" width="45">
                    <template #trigger>
                        <span class="inline-flex rounded-md">
                            <button type="button"
                                class="inline-flex items-center px-3 py-2 leading-4 transition ease-in-out duration-150">

                                <Icon name="project" />
                            </button>
                        </span>
                    </template>

                    <template #content>
                        <!-- TODO PENDIENTE AJUSTAR RUTA DE EDITAR -->
                        <BreezeDropdownLink method="post" as="button">
                            Editar
                        </BreezeDropdownLink>
                        <!-- TODO PENDIENTE AJUSTAR RUTA DE ELIMINAR -->
                        <BreezeDropdownLink method="post" as="button">
                            Eliminar
                        </BreezeDropdownLink>
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
        </div>
        </Link>
        <p v-if="project.isIncomplete && isAssociatedUser" class="text-xs border-black border-1 bg-yellow-500 color-black text-center">¡Datos del proyecto incompleto!</p>
    </div>
    <!-- PROJECT DESIGN -->
</template>

<style scoped>
.validity-font {
    color: #9E9E9E;
    font-size: 15px;
}
</style>
