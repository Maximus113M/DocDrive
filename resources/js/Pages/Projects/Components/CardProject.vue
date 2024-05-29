<script setup>
import { Link } from '@inertiajs/inertia-vue3';
import FoldersDropdown from '@/Shared/FoldersDropdown.vue';
import BreezeDropdownLink from '@/Components/DropdownLink.vue';
import Icon from '@/Shared/Icon.vue';

defineProps(['year', 'id']);

const onClicks = (event) => {
    event.preventDefault();
}

</script>

<template>
    <!-- PROJECT DESIGN -->
    <div class="col position-relative" style="max-width: 300px;">
        <Link :href="route('validity.projects', { 'validityYear': year })" class="text-decoration-none">
        <div class="d-flex flex-column align-items-center border-3 rounded-4 py-1 bg-white">
            <div class="position-absolute top-1 end-1" @click="onClicks">
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
                Vigencia
            </div>
            <h3 class="text-black"><strong>{{ year }}</strong></h3>
        </div>
        </Link>
    </div>
    <!-- PROJECT DESIGN -->
</template>

<style scoped>
.validity-font {
    color: #9E9E9E;
    font-size: 15px;
}
</style>
