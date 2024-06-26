<script setup>
import { Link } from '@inertiajs/inertia-vue3';
import FoldersDropdown from '@/Shared/FoldersDropdown.vue';
import Icon from '@/Shared/Icon.vue';


const props = defineProps({
    id: { type: Object },
    name: { type: Object, required: true },
});


const onClicks = (event) => {
    event.preventDefault();
}


</script>

<template>
    <!-- PROJECT DESIGN -->
    <div class="col position-relative" style="max-width: 300px;">

        <Link :href="route('shared.index', {'folderID': id})"
            class="text-decoration-none">

        <div class="d-flex flex-column justify-center align-items-center border-3 rounded-4 py-1 bg-white h-40">
            <!-- BODY -->
            <div v-if="$page.props.auth.user != null && $page.props.auth.user.role.name == 'admin'" class="position-absolute top-1 end-1"
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
                            @click="openModalUpdate">
                            Editar
                        </button>
                        <button
                            class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                            @click="openModalDelete">
                            Eliminar
                        </button>
                    </template>
                </FoldersDropdown>
            </div>
            <Icon name="sharedFolder" />


            <div v-if="!folder" class="validity-font">
                Recurso compartido
            </div>
            <div class="max-h-12 max-w-44 overflow-hidden text-center">
                <h5 v-if="!folder" class="text-black text-wrap text-ellipsis"><strong> {{ props.name.length > 25
                    ?
                    `${props.name.substring(0, 25)}...` : props.name }} </strong></h5>
                <h5 v-else class="text-black text-wrap text-ellipsis"><strong> {{ props.name.length > 25 ?
                    `${props.name.substring(0, 25)}...` : props.name }} </strong></h5>

            </div>
        </div>
        </Link>
    </div>
    <!-- PROJECT DESIGN -->



    <!-- MODAL EDIT SHARED FOLDER-->
   
</template>

<style scoped>
.validity-font {
    color: #9E9E9E;
    font-size: 15px;
}
</style>
