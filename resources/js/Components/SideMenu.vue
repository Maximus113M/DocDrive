<script setup>
import { computed, ref } from 'vue';
import BreezeDropdown from '@/Components/Dropdown.vue';
import BreezeDropdownLink from '@/Components/DropdownLink.vue';
import BreezeNavLink from '@/Components/NavLink.vue';
import BreezeResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/inertia-vue3';

const showingNavigationDropdown = ref(false);


defineProps({
    role: String,
    isGuest:Boolean
})

</script>

<template>
    <aside id="default-sidebar"
        class="fixed top-0 left-0 z-40 w-45 h-screen transition-transform -translate-x-full sm:translate-x-0"
        aria-label="Sidebar">
        <div class="h-full px-3 py-4 overflow-y-auto bg-lime-600">
            <ul class="space-y-2 pl-0 font-medium">
                <li>
                    <div class="pb-16">
                        <svg class="m-auto" xmlns="http://www.w3.org/2000/svg" width="50px" height="50px"
                            viewBox="0 0 24 24">
                            <g fill="none">
                                <path fill="white" fill-rule="evenodd"
                                    d="M2 10c0-3.771 0-5.657 1.172-6.828C4.343 2 6.229 2 10 2h4c3.771 0 5.657 0 6.828 1.172C22 4.343 22 6.229 22 10v4c0 3.771 0 5.657-1.172 6.828C19.657 22 17.771 22 14 22h-4c-3.771 0-5.657 0-6.828-1.172C2 19.657 2 17.771 2 14zm5.739 6.447a7 7 0 0 1 11.023 3.741a1 1 0 0 1-1.932.518a5 5 0 0 0-9.66 0a1 1 0 1 1-1.931-.518a7 7 0 0 1 2.5-3.741M10 9a2 2 0 1 1 4 0a2 2 0 0 1-4 0m2-4a4 4 0 1 0 0 8a4 4 0 0 0 0-8"
                                    clip-rule="evenodd" />
                                <rect width="19" height="19" x="2.5" y="2.5" stroke="white" rx="3.5" />
                            </g>
                        </svg>
                        <div class="text-center font-bold text-lm text-white">{{ !isGuest ? $page.props.auth.user.email : "Visitante" }}</div>

                    </div>

                </li>
                <li class="py-2">
                    <div class="lg:ml-2 hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                        <BreezeNavLink :href="route('validity.index')">

                            <svg class="mr-2" xmlns="http://www.w3.org/2000/svg" width="30px" height="30px"
                                viewBox="0 0 24 24">
                                <g fill="none">
                                    <path stroke="white"
                                        d="M2.5 15.1c0-.56 0-.84.109-1.054a1 1 0 0 1 .437-.437C3.26 13.5 3.54 13.5 4.1 13.5h.237c.245 0 .367 0 .482.028c.102.024.2.065.29.12c.1.061.187.148.36.32l.062.063c.173.173.26.26.36.322c.09.055.188.095.29.12c.115.027.237.027.482.027H7.9c.56 0 .84 0 1.054.11a1 1 0 0 1 .437.436c.109.214.109.494.109 1.054v1.8c0 .56 0 .84-.109 1.054a1 1 0 0 1-.437.437c-.214.109-.494.109-1.054.109H4.1c-.56 0-.84 0-1.054-.109a1 1 0 0 1-.437-.437C2.5 18.74 2.5 18.46 2.5 17.9zm12 0c0-.56 0-.84.109-1.054a1 1 0 0 1 .437-.437c.214-.109.494-.109 1.054-.109h.237c.245 0 .367 0 .482.028c.102.024.2.065.29.12c.1.061.187.148.36.32l.062.063c.173.173.26.26.36.322c.09.055.188.095.29.12c.115.027.237.027.482.027H19.9c.56 0 .84 0 1.054.11a1 1 0 0 1 .437.436c.109.214.109.494.109 1.054v1.8c0 .56 0 .84-.109 1.054a1 1 0 0 1-.437.437c-.214.109-.494.109-1.054.109h-3.8c-.56 0-.84 0-1.054-.109a1 1 0 0 1-.437-.437c-.109-.214-.109-.494-.109-1.054zm-6-9c0-.56 0-.84.109-1.054a1 1 0 0 1 .437-.437C9.26 4.5 9.54 4.5 10.1 4.5h.237c.245 0 .367 0 .482.028a1 1 0 0 1 .29.12c.1.061.187.148.36.32l.062.063c.173.174.26.26.36.322a1 1 0 0 0 .29.12c.115.027.237.027.482.027H13.9c.56 0 .84 0 1.054.11a1 1 0 0 1 .437.436c.109.214.109.494.109 1.054v1.8c0 .56 0 .84-.109 1.054a1 1 0 0 1-.437.437c-.214.109-.494.109-1.054.109h-3.8c-.56 0-.84 0-1.054-.109a1 1 0 0 1-.437-.437C8.5 9.74 8.5 9.46 8.5 8.9zm-5 5.9V8.6c0-.56 0-.84.109-1.054a1 1 0 0 1 .437-.437C4.26 7 4.54 7 5.1 7H6m14.5 5V8.6c0-.56 0-.84-.109-1.054a1 1 0 0 0-.437-.437C19.74 7 19.46 7 18.9 7H18" />
                                    <path fill="white" d="M4.5 5.5L7 7L4.5 8.5zm15 0L17 7l2.5 1.5z" />
                                </g>
                            </svg>
                            Vigencias

                        </BreezeNavLink>
                    </div>

                </li>

                <li v-if="!isGuest" class="py-2">
                    <div class="lg:ml-2 hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">

                        <BreezeNavLink v-if="role === 'admin' || role === 'investigator'"
                            :href="route('collaborator.index')">
                            <svg class="mr-2" xmlns="http://www.w3.org/2000/svg" width="25px" height="25px"
                                viewBox="0 0 16 16">
                                <path fill="white" fill-rule="evenodd"
                                    d="M5.5 6a1.5 1.5 0 1 0 0-3a1.5 1.5 0 0 0 0 3m0 1.5a3 3 0 1 0 0-6a3 3 0 0 0 0 6m-3.029 2.886c-.777.54-.971 1.063-.971 1.306c0 .446.362.808.808.808h6.384a.808.808 0 0 0 .808-.808c0-.244-.194-.767-.971-1.306C7.792 9.875 6.719 9.5 5.5 9.5c-1.218 0-2.292.375-3.029.886M0 11.692C0 9.846 2.475 8 5.5 8c1.18 0 2.278.281 3.177.734A5.671 5.671 0 0 1 11.5 8c2.475 0 4.5 1.538 4.5 3.077A1.923 1.923 0 0 1 14.077 13h-3.483c-.416.604-1.113 1-1.902 1H2.308A2.308 2.308 0 0 1 0 11.692m10.991-.192h3.086c.234 0 .423-.19.423-.423c0-.103-.096-.472-.688-.89c-.554-.393-1.375-.687-2.312-.687c-.517 0-.999.09-1.42.236c.526.534.854 1.146.911 1.764M12.5 5a1 1 0 1 1-2 0a1 1 0 0 1 2 0M14 5a2.5 2.5 0 1 1-5 0a2.5 2.5 0 0 1 5 0"
                                    clip-rule="evenodd" />
                            </svg>
                            Colaboradores
                        </BreezeNavLink>
                    </div>
                </li>

                <li v-if="!isGuest" class="py-2">
                    <div class="lg:ml-2 hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">

                        <BreezeNavLink v-if="role === 'admin'" :href="route('investigator.index')">
                            <svg class="mr-2" xmlns="http://www.w3.org/2000/svg" width="25px" height="25px"
                                viewBox="0 0 24 24">
                                <path fill="white"
                                    d="M5 21q-1.275 0-1.812-1.137t.262-2.113L9 11V5H8q-.425 0-.712-.288T7 4t.288-.712T8 3h8q.425 0 .713.288T17 4t-.288.713T16 5h-1v6l5.55 6.75q.8.975.263 2.113T19 21zm0-2h14l-6-7.3V5h-2v6.7zm7-7" />
                            </svg>
                            Investigadores
                        </BreezeNavLink>
                    </div>
                </li>

                <li v-if="isGuest" class="py-2">
                    <div class="lg:ml-2 hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                        <Link class="font-bold no-underline cursor-pointer text-lime-600 bg-white px-5 py-2" :href="route('login')">Ingresar</Link>
                    </div>
                </li>

               
            </ul>
        </div>
    </aside>

    <nav class="bg-white border-b border-gray-100 w-11/12 float-right">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex ml-10">
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex py-2 flex-col">
                        <h4 class="text-lime-600 font-bold my-0">DocDrive</h4>
                        <h6 class="text-black font-medium mx-0">Hola {{ !isGuest ? $page.props.auth.user.name : "visitante" }}</h6>
                    </div>

                </div>

                <div class="hidden sm:flex sm:items-center sm:ml-6">
                    <div class="ml-3 relative">
                        <BreezeDropdown align="right" width="48">
                            <template #trigger>
                                <span class="inline-flex rounded-md">
                                    <button type="button"
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                        {{ !isGuest ? role : "Visitante" }}

                                        <svg v-if="!isGuest" class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </span>
                            </template>

                            <template v-if="!isGuest" #content>
                                <BreezeDropdownLink :href="route('logout')" method="post" as="button">
                                    Log Out
                                </BreezeDropdownLink>
                            </template>
                        </BreezeDropdown>
                    </div>
                </div>

                <div class="-mr-2 flex items-center sm:hidden">
                    <button @click="showingNavigationDropdown = !showingNavigationDropdown"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path
                                :class="{ 'hidden': showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                            <path
                                :class="{ 'hidden': !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div :class="{ 'block': showingNavigationDropdown, 'hidden': !showingNavigationDropdown }" class="sm:hidden">


            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ !isGuest ? role : "Visitante" }}</div>
                </div>

                <div v-if="!isGuest" class="mt-3 space-y-1">
                    <BreezeResponsiveNavLink :href="route('logout')" method="post" as="button">
                        Log Out
                    </BreezeResponsiveNavLink>
                </div>
            </div>
        </div>
    </nav>
</template>
