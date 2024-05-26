<script setup>
import { Link } from '@inertiajs/inertia-vue3';
import FoldersDropdown from '@/Shared/FoldersDropdown.vue';
import BreezeDropdownLink from '@/Components/DropdownLink.vue';

defineProps(['year', 'id']);

const onClicks = (event) => {
    event.preventDefault();
}

</script>

<template>
    <!-- FOLDER DESIGN -->
    <div class="col position-relative" style="max-width: 300px;">
        <Link :href="route('validity.projects', { 'validityYear': year })" class="text-decoration-none">
        <div class="d-flex flex-column align-items-center border-3 rounded-4 py-1 bg-white">
            <div class="position-absolute top-1 end-1" @click="onClicks">
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
    <!-- FOLDER DESIGN -->
</template>

<style scoped>
.validity-font {
    color: #9E9E9E;
    font-size: 15px;
}
</style>
