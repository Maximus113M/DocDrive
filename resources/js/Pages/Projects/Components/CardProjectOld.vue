<script setup>
import { Link, usePage } from '@inertiajs/inertia-vue3';
import { onMounted, ref } from 'vue';

const { project } = defineProps(['project']);

const isAssociatedUser = ref(null);


const verifiyAssociatedUser = () => {
    for (let index = 0; index < project.users.length; index++) {
        const user = project.users[index];
        if (user.id == usePage().props.value.auth.user.id) {
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
    <div class="card">
        <div class="card-body">
            <h6 class="card-title">{{ project }}</h6>
            <!--<Link :href="route('validity.projects', {'validityYear' : year})" class="btn btn-primary">Ver más</Link>-->
        </div>
    </div>
</template>