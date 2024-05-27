<template>
  <div class="flex-col relative h-full">
    <!-- <div>
    <BreezeNavLink :href="route('validity.index')">

      <svg class="mr-2" xmlns="http://www.w3.org/2000/svg" width="30px" height="30px" viewBox="0 0 24 24">
        <g fill="none">
          <path stroke="white"
            d="M2.5 15.1c0-.56 0-.84.109-1.054a1 1 0 0 1 .437-.437C3.26 13.5 3.54 13.5 4.1 13.5h.237c.245 0 .367 0 .482.028c.102.024.2.065.29.12c.1.061.187.148.36.32l.062.063c.173.173.26.26.36.322c.09.055.188.095.29.12c.115.027.237.027.482.027H7.9c.56 0 .84 0 1.054.11a1 1 0 0 1 .437.436c.109.214.109.494.109 1.054v1.8c0 .56 0 .84-.109 1.054a1 1 0 0 1-.437.437c-.214.109-.494.109-1.054.109H4.1c-.56 0-.84 0-1.054-.109a1 1 0 0 1-.437-.437C2.5 18.74 2.5 18.46 2.5 17.9zm12 0c0-.56 0-.84.109-1.054a1 1 0 0 1 .437-.437c.214-.109.494-.109 1.054-.109h.237c.245 0 .367 0 .482.028c.102.024.2.065.29.12c.1.061.187.148.36.32l.062.063c.173.173.26.26.36.322c.09.055.188.095.29.12c.115.027.237.027.482.027H19.9c.56 0 .84 0 1.054.11a1 1 0 0 1 .437.436c.109.214.109.494.109 1.054v1.8c0 .56 0 .84-.109 1.054a1 1 0 0 1-.437.437c-.214.109-.494.109-1.054.109h-3.8c-.56 0-.84 0-1.054-.109a1 1 0 0 1-.437-.437c-.109-.214-.109-.494-.109-1.054zm-6-9c0-.56 0-.84.109-1.054a1 1 0 0 1 .437-.437C9.26 4.5 9.54 4.5 10.1 4.5h.237c.245 0 .367 0 .482.028a1 1 0 0 1 .29.12c.1.061.187.148.36.32l.062.063c.173.174.26.26.36.322a1 1 0 0 0 .29.12c.115.027.237.027.482.027H13.9c.56 0 .84 0 1.054.11a1 1 0 0 1 .437.436c.109.214.109.494.109 1.054v1.8c0 .56 0 .84-.109 1.054a1 1 0 0 1-.437.437c-.214.109-.494.109-1.054.109h-3.8c-.56 0-.84 0-1.054-.109a1 1 0 0 1-.437-.437C8.5 9.74 8.5 9.46 8.5 8.9zm-5 5.9V8.6c0-.56 0-.84.109-1.054a1 1 0 0 1 .437-.437C4.26 7 4.54 7 5.1 7H6m14.5 5V8.6c0-.56 0-.84-.109-1.054a1 1 0 0 0-.437-.437C19.74 7 19.46 7 18.9 7H18" />
          <path fill="white" d="M4.5 5.5L7 7L4.5 8.5zm15 0L17 7l2.5 1.5z" />
        </g>
      </svg>
      Vigencias

    </BreezeNavLink>
  </div> -->


    <div class="mb-3">
      <Link class="group flex items-center py-3 text-decoration-none" :href="route('validity.index')"
        @click="selectedIndex = 0">
      <Icon name="dashboard" class="mr-2 w-7 h-7"
        :class="selectedIndex === 0 ? 'fill-white' : 'fill-gray-300 group-hover:fill-white'" />
      <div :class="selectedIndex === 0 ? 'text-white text-lg font-bold' : 'text-gray-300 group-hover:text-white'">
        Dashboard</div>
      </Link>
    </div>

    <div v-if="role === 'admin'" class="mb-3">
      <Link class="group flex items-center py-3 text-decoration-none" :href="route('investigator.index')"
        @click="selectedIndex = 1">
      <Icon name="investigators" class="mr-2 w-7 h-7"
        :class="selectedIndex === 1 ? 'fill-white' : 'fill-gray-300 group-hover:fill-white'" />
      <div :class="selectedIndex === 1 ? 'text-white text-lg font-bold' : 'text-gray-300 group-hover:text-white '">
        Investigadores
      </div>
      </Link>
    </div>

    <div v-if="role === 'admin' || role === 'investigator'" class="mb-3">
      <Link class="group flex items-center py-3 text-decoration-none" :href="route('collaborator.index')"
        @click="selectedIndex = 2">
      <Icon name="collaborators" class="mr-2 w-7 h-7"
        :class="selectedIndex === 2 ? 'fill-white' : 'fill-gray-300 group-hover:fill-white'" />
      <div :class="selectedIndex === 2 ? 'text-white text-lg font-bold' : 'text-gray-300 group-hover:text-white'">
        Colaboradores
      </div>
      </Link>
    </div>

    <div v-if="isGuest" class="mb-3">
      <Link class="group flex items-center py-3 text-decoration-none" href="/login">
      <div class="lg:ml-2 hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
        <Link class="font-bold no-underline cursor-pointer text-lime-600 bg-white px-5 py-2" :href="route('login')">
        Ingresar</Link>
      </div>
      </Link>
    </div>

    <div v-if="isSideMenu" class="flex justify-center opacity-30 lg:mt-4 lg:mb-5  xxl:my-5">
      <img src="/images/logos/logo-sena-blanco-fix.png" width="140px" alt="logo-sena">
    </div>

    <div v-if="!isGuest" class="md:mt-5">
      <Link class="group flex items-center py-3 text-decoration-none" method="post" href="/logout" @click="selectedIndex = 4">
      <Icon name="logout" class="mr-2 w-7 h-7"
        :class="selectedIndex === 4 ? 'fill-white' : 'fill-gray-300 group-hover:fill-white'" />
      <div :class="selectedIndex === 4 ? 'text-white text-lg font-bold' : 'text-gray-300 group-hover:text-white'">Cerrar
        Sesión
      </div>
      </Link>
    </div>
  </div>
</template>

<script setup>
import Icon from '@/Shared/Icon.vue'
import { Link } from '@inertiajs/inertia-vue3';
import { ref, onMounted} from 'vue';
import BreezeNavLink from '@/Components/NavLink.vue';


//const 

defineProps({
  role: String,
  isGuest: Boolean,
  isSideMenu: Boolean
})

onMounted(()=>{
  console.log('Mamamahuevboooooooooooooooooo')
})

const selectedIndex = ref(0)

</script>

<style scoped>
.container {
  width: 130px;
  background-image: url('/images/logos/logo-sena-blanco-fix.png');
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
}
</style>