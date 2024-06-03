<script setup>
import NavMenu from '@/Components/SideMenu.vue'
import LayoutDropdown from '@/Shared/LayoutDropdown.vue';
import MainMenu from '@/Shared/MainMenu.vue';
import Icon from '@/Shared/Icon.vue';
import BreezeDropdownLink from '@/Components/DropdownLink.vue';
import BreezeDropdown from '@/Components/Dropdown.vue';


defineProps({
    role: String
})


</script>

<template>
    <div>
        <div class="md:flex md:flex-col">
            <div class="md:flex md:flex-col md:h-screen">
                <!-- BARRA SUPERIOR -->
                <div class="md:flex md:shrink-0 max-h-28">
                    <!-- Barra izquierda -->
                    <div class="flex items-center justify-between px-6 py-4 md:shrink-0 md:justify-center md:w-56 md:py-0"
                        style="background-color: #39A900;">

                        <!-- TODO INSERTAR IMAGEN o dejar Icon, definir -->
                        <div class="hidden flex-col md:block">
                            <div class="bg-white rounded-full p-3">
                                <Icon :name="role" />
                            </div>

                        </div>
                        <h2 class="md:hidden" style="color: white;"><strong>DocDrive</strong></h2>

                        <LayoutDropdown class="md:hidden" placement="bottom-end">
                            <template #default>
                                <Icon name="menu" class="md:hidden" />
                            </template>
                            <template #dropdown>

                                <div class="md:hidden mt-2 px-8 py-4 rounded shadow-lg"
                                    style="background-color: #39A900;">
                                    <MainMenu :role="role" :is-side-menu="false" />
                                </div>
                            </template>
                        </LayoutDropdown>
                    </div>

                    <!-- Barra central -->
                    <div
                        class="hidden md:flex items-center justify-between w-full text-sm bg-white border-b md:px-12 md:py-4">
                        <div>
                            <h2 style="color: #39A900;"><strong>DocDrive</strong></h2>
                            <!-- TODO Pasar usuario o nombre -->
                            <div class="text-sm">
                                Hola Camilo
                            </div>
                        </div>
                        <div class="flex items-center ml-6">
                            <div class="ml-3 relative">
                                <BreezeDropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <Icon :name="role" />
                                            <button type="button"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                                {{ role === 'guest' ? "Visitante" : role }}

                                                <svg v-if="role !== 'guest'" class="ml-2 -mr-0.5 h-4 w-4"
                                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                    fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>
                                    <template v-if="role !== 'guest'" #content>
                                        <BreezeDropdownLink :href="route('logout')" method="post" as="button">
                                            Cerrar Sesión
                                        </BreezeDropdownLink>
                                    </template>
                                </BreezeDropdown>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="md:flex md:grow md:overflow-hidden">
                    <MainMenu :role="role" :is-side-menu="true"
                        class="hidden shrink-0 p-4 w-56 overflow-y-auto md:block" style="background-color: #39A900;" />

                    <!-- CONTENT -->
                    <div class="w-full overflow-y-auto pt-3 px-3" style="background-color: #EBEBEB;" scroll-region>
                        <div class="bg-white h-full w-full overflow-y-auto rounded-t-2xl">
                            <slot :role="role" class="shrink-0 md:flex" to="content" />
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


    <!-- <div>
        <div class="min-h-screen bg-gray-100">      
            <NavMenu :isGuest="false" :role="role"/>
            <header class="bg-white shadow" v-if="$slots.header">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <main>
                <slot />
            </main>
        </div>
    </div> -->


</template>
