<template>
  <div class="flex-col relative h-full">
    
    <div class="mb-3">
      <Link class="group flex items-center py-3 text-decoration-none" method="get"  :href="route('validity.index')"
        @click="selectedIndex = 0">
      <Icon name="dashboard" class="mr-2 w-7 h-7"
        :class="selectedIndex === 0 ? 'fill-white' : 'fill-gray-300 group-hover:fill-white'" />
      <div :class="selectedIndex === 0 ? 'text-white text-lg font-bold' : 'text-gray-300 group-hover:text-white'">
        Dashboard</div>
      </Link>
    </div>

    <div v-if="role === 'admin'" class="mb-3">
      <Link class="group flex items-center py-3 text-decoration-none" method="get" :href="route('investigator.index')"
        @click="selectedIndex = 1">
      <Icon name="investigators" class="mr-2 w-7 h-7"
        :class="selectedIndex === 1 ? 'fill-white' : 'fill-gray-300 group-hover:fill-white'" />
      <div :class="selectedIndex === 1 ? 'text-white text-lg font-bold' : 'text-gray-300 group-hover:text-white '">
        Investigadores
      </div>
      </Link>
    </div>

    <div v-if="role === 'admin' || role === 'investigator'" class="mb-3">
      <Link class="group flex items-center py-3 text-decoration-none" method="get" :href="route('collaborator.index')"
        @click="selectedIndex = 2">
      <Icon name="collaborators" class="mr-2 w-7 h-7"
        :class="selectedIndex === 2 ? 'fill-white' : 'fill-gray-300 group-hover:fill-white'" />
      <div :class="selectedIndex === 2 ? 'text-white text-lg font-bold' : 'text-gray-300 group-hover:text-white'">
        Colaboradores
      </div>
      </Link>
    </div>

    <div v-if="role === 'admin'" class="mb-3">
      <Link class="group flex items-center py-3 text-decoration-none" method="get" :href="route('document-category.index')"
        @click="selectedIndex = 3">
      <Icon name="document-categories" class="mr-2 w-7 h-7"
        :class="selectedIndex === 3 ? 'fill-white' : 'fill-gray-300 group-hover:fill-white'" />
      <div :class="selectedIndex === 3 ? 'text-white text-lg font-bold' : 'text-gray-300 group-hover:text-white'">
        Categorías
      </div>
      </Link>
    </div>
    
    <div v-if="role != 'guest'" class="mb-3">
      <Link class="group flex items-center py-3 text-decoration-none" method="get" :href="route('user.edit')"
      @click="selectedIndex = 4">
      <Icon name="settings" class="mr-2 w-7 h-7"
      :class="selectedIndex === 4 ? 'fill-white' : 'fill-gray-300 group-hover:fill-white'" />
      <div :class="selectedIndex === 4 ? 'text-white text-lg font-bold' : 'text-gray-300 group-hover:text-white'">
        Ajustes
      </div>
    </Link>
  </div>
  
  <div v-if="isSideMenu" class="flex justify-center opacity-30 lg:mt-4 lg:mb-5  xxl:my-5 select-none">
    <img src="/images/logos/logo-sena-blanco-fix.png" width="140px" alt="logo-sena">
  </div>

  <div v-if="role != 'guest'" class="md:mt-5">
    <Link class="group flex items-center py-3 text-decoration-none" method="post" :href="route('logout')" @click="selectedIndex = 4">
      <Icon name="logout" class="mr-2 w-7 h-7"
        :class="selectedIndex === 5 ? 'fill-white' : 'fill-gray-300 group-hover:fill-white'" />
      <div :class="selectedIndex === 5 ? 'text-white text-lg font-bold' : 'text-gray-300 group-hover:text-white'">Cerrar
        Sesión
      </div>
    </Link>
  </div>

  <div v-if="role == 'guest'" class="mb-3">
    <Link class="group flex items-center py-3 text-decoration-none"  method="get" :href="route('login')" >
    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex lg:ml-2">
      <div class="font-bold no-underline cursor-pointer text-lime-600 bg-white px-5 py-2 rounded-lg">
      Ingresar</div>
    </div>
    </Link>
  </div>

  </div>
</template>

<script setup>
import Icon from '@/Shared/Icon.vue'
import { Link } from '@inertiajs/inertia-vue3';
import { ref, onMounted} from 'vue';

defineProps({
  role: String,
  isSideMenu: Boolean
})

onMounted(()=>{
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