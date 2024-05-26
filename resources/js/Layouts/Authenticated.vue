<script setup>
import NavMenu from '@/Components/SideMenu.vue'
import LayoutDropdown from '@/Shared/LayoutDropdown.vue';
import MainMenu from '@/Shared/MainMenu.vue';
import { Link } from '@inertiajs/inertia-vue3';
import Icon from '@/Shared/Icon.vue';
import FoldersDropdown from '@/Shared/FoldersDropdown.vue';
import BreezeDropdownLink from '@/Components/DropdownLink.vue';


defineProps({
    role: String
})


</script>

<template>
    <div>
        <div class="md:flex md:flex-col">
            <div class="md:flex md:flex-col md:h-screen">
                <div class="md:flex md:shrink-0">

                    <div class="flex items-center justify-between px-6 py-4 md:shrink-0 md:justify-center md:w-56"
                        style="background-color: #39A900;">
                        <h2 class="md:hidden" style="color: white;"><strong>DocDrive</strong></h2>

                        <LayoutDropdown class="md:hidden" placement="bottom-end">
                            <template #default>
                                <Icon name="menu" class="md:hidden"/>
                            </template>
                            <template #dropdown>

                                <div class="mt-2 px-8 py-4 bg-indigo-800 rounded shadow-lg">
                                    <MainMenu :isGuest="false" :role="role" />
                                </div>
                            </template>
                        </LayoutDropdown>
                    </div>

                    <div
                        class="md:text-md flex items-center justify-between p-4 w-full text-sm bg-white border-b md:px-12 md:py-0">
                        <div class="mr-4 mt-1">{{ role }}</div>

                        <LayoutDropdown class="mt-1" placement="bottom-end">
                            <template #default>
                                <div class="group flex items-center cursor-pointer select-none">
                                    <div
                                        class="mr-1 text-gray-700 group-hover:text-indigo-600 focus:text-indigo-600 whitespace-nowrap">
                                        <span>{{ role }}</span>
                                    </div>
                                    <Icon
                                        class="w-5 h-5 fill-gray-700 group-hover:fill-indigo-600 focus:fill-indigo-600"
                                        name="cheveron-down" />
                                </div>
                            </template>

                            <template #dropdown>
                                <div class="mt-2 py-2 text-sm bg-white rounded shadow-xl">
                                    <Link class="block px-6 py-2 w-full text-left hover:text-white hover:bg-indigo-500"
                                        href="/logout" method="delete" as="button">Logout</Link>
                                </div>
                            </template>
                        </LayoutDropdown>
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
