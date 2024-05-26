<template>
  <div>
    <div class="mb-4">
      <Link class="group flex items-center py-3" href="/validity.index">
      <Icon name="dashboard" class="mr-2 w-5 h-5"
        :class="isUrl('validity.index') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
      <div :class="isUrl('validity.index') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Dashboard</div>
      </Link>
    </div>

    <div v-if="role === 'admin'" class="mb-4">
      <Link class="group flex items-center py-3" href="/collaborator.index">
      <Icon name="collaborators" class="mr-2 w-5 h-5"
        :class="isUrl('collaborator.index') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
      <div :class="isUrl('collaborator.index') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Colaboradores
      </div>
      </Link>
    </div>

    <div v-if="role === 'admin' || role === 'investigator'" class="mb-4">
      <Link class="group flex items-center py-3" href="/investigator.index">
      <Icon name="investigators" class="mr-2 w-5 h-5"
        :class="isUrl('investigator.index') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
      <div :class="isUrl('investigator.index') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Investigadores
      </div>
      </Link>
    </div>

    <div v-if="isGuest" class="mb-4">
      <Link class="group flex items-center py-3" href="/login">
      <div class="lg:ml-2 hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
        <Link class="font-bold no-underline cursor-pointer text-lime-600 bg-white px-5 py-2" :href="route('login')">
        Ingresar</Link>
      </div>
      </Link>
    </div>

  </div>
</template>

<script setup>
import Icon from '@/Shared/Icon.vue'
import { Link } from '@inertiajs/inertia-vue3';


defineProps({
  role: String,
  isGuest: Boolean
})

const isUrl = (...urls) => {
 /* let currentUrl = this.$page.url.substr(1)
  if (urls[0] === '') {
    return currentUrl === ''
  }
  return urls.filter((url) => currentUrl.startsWith(url)).length*/
}

</script>
