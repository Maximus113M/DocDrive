<script setup>
import Card from '@/Pages/Validity/Components/CardValidity.vue';
import FoldersDropdown from '@/Shared/FoldersDropdown.vue';
import BreezeDropdownLink from '@/Components/DropdownLink.vue';
import { useForm } from '@inertiajs/inertia-vue3'
import { ref } from 'vue';


const modal = ref(null)

const form = useForm({
    year: null,
})

defineProps({
    validities: Object,
    isAuthenticated: Boolean,
    role: String,
})


const saveValidity = () => {
    form.post(route('validity.store'), {
        onSuccess: () => closeModal()
    })
}

const closeModal = () => {
    const modalBootstrap = bootstrap.Modal.getInstance(modal.value)
    modalBootstrap.hide()
}

const onClicks = (event) => {
    event.preventDefault();
}

</script>

<template>
    <div class="p-5 relative">
        <!-- Buscador -->
        <div class="absolute top-1 right-20">
            
        </div>

        <div class="pt-3 row row-cols-2 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 row-cols-xxl-5 g-5">

            <button v-if="role == 'admin' && isAuthenticated" class="col" data-bs-toggle="modal"
                data-bs-target="#modalSaveValidity">
                <div class="folder border-3 rounded-4 py-3 bg-white">
                    <svg xmlns="http://www.w3.org/2000/svg" height="84px" viewBox="0 -960 960 960" width="84px"
                        fill="#000000">
                        <path
                            d="M453-280h60v-166h167v-60H513v-174h-60v174H280v60h173v166Zm27.27 200q-82.74 0-155.5-31.5Q252-143 197.5-197.5t-86-127.34Q80-397.68 80-480.5t31.5-155.66Q143-709 197.5-763t127.34-85.5Q397.68-880 480.5-880t155.66 31.5Q709-817 763-763t85.5 127Q880-563 880-480.27q0 82.74-31.5 155.5Q817-252 763-197.68q-54 54.31-127 86Q563-80 480.27-80Z" />
                    </svg>
                    <h6 class="py-1 text-gray-400"><strong>Nueva Vigencia</strong></h6>
                </div>
            </button>

            <div class="mt-auto mb-auto" v-if="validities.length < 1">
                <h1>No hay vigencias</h1>
            </div>

            <div v-for="v in validities">
                <Card :id="v.id" :year="v.year" />
            </div>
        </div>
    </div>


    <!-- Modal -->


    <div ref="modal" class="modal fade" id="modalSaveValidity" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" style="width: 350px; height: 600px;">
            <div class="modal-content position-relative p-3" style="max-height: 400px;">
                <div class="d-flex flex-row justify-center px-3">
                    <h4 class="my-3" style="color: #39A900;"><strong>Nueva vigencia</strong></h4>

                    <button type="button" class="btn-close position-absolute top-1 end-3" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body px-3">
                    <form @submit.prevent="saveValidity">
                        <div class="mb-3">
                            <label for="year" class="form-label">Ingrese el año:</label>
                            <input v-model="form.year" type="number" class="form-control" id="year">
                            <div v-if="form.errors.year">{{ form.errors.year }}</div>
                        </div>
                        <div class="row justify-center p-3 mt-5">
                            <button type="submit" class="btn py-2"
                                style="background-color: #39A900; color: white; "><strong>Crear
                                    vigencia</strong></button>
                        </div>
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

.btn-add {
    height: 75px;
    margin-right: 60px;
    width: 75px;
    border-radius: 50%;
}

.folder {
    display: flex;
    flex-direction: column;
    align-items: center;
    max-width: 300px;
}

.validity-font {
    color: #9E9E9E;
    font-size: 15px;
}
</style>