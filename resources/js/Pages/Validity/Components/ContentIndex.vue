<script setup>
import Card from '@/Pages/Validity/Components/CardValidity.vue';
import { useForm } from '@inertiajs/inertia-vue3'

const form = useForm({
    year: null,
})

defineProps({
    validities: Object,
    isAuthenticated: Boolean,
    role: String,
})


const saveValidity = () => {
    form.post(route('validity.store'))
}


</script>

<template>
    <div class="container">

        <div id="title">
            <h2>Vigencias</h2>
        </div>
        <div class="validities-cards">
            <button v-if="role == 'admin' && isAuthenticated" type="button"
                class="btn-add btn btn-primary mt-auto mb-auto" data-bs-toggle="modal"
                data-bs-target="#modalSaveValidity">
                <svg xmlns="http://www.w3.org/2000/svg" width="50px" height="50px" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M11 13H5v-2h6V5h2v6h6v2h-6v6h-2z" />
                </svg>
            </button>
            <div class="mt-auto mb-auto" v-if="validities.length < 1"><h1>No hay vigencias</h1></div>
            <div v-for="v in validities">
                <Card :id="v.id" :year="v.year" />
            </div>
        </div>
    </div>


    <!-- Modal -->


    <div class="modal fade" id="modalSaveValidity" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Crear nueva vigencia</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="saveValidity">
                        <div class="mb-3">
                            <label for="year" class="form-label">Año</label>
                            <input v-model="form.year" type="number" class="form-control" id="year">
                            <div v-if="form.errors.year">{{ form.errors.year }}</div>
                        </div>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="ml-4 btn btn-primary">Crear vigencia</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


</template>

<style scoped>
#title>h2 {
    font-weight: bold;
    text-align: center;
}

#title {
    padding-bottom: 40px;
}

.validities-cards {
    justify-content: center;
    display: flex;
}

.btn-add {
    height: 75px;
    margin-right: 60px;
    width: 75px;
    border-radius: 50%;
}
</style>