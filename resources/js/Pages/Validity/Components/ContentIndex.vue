<script setup>
import Card from '@/Pages/Validity/Components/CardValidity.vue';
import Icon from '@/Shared/Icon.vue';
import SharedFolderCard from '../../SharedFolder/Components/CardSharedFolder.vue'
import { CustomAlertsService } from '@/services/customAlerts';
import { Link, useForm, usePage } from '@inertiajs/inertia-vue3'
import { ref, onUpdated, onMounted, watch } from 'vue';

const props = defineProps({
    validities: { type: Array, required: true },
    isAuthenticated: Boolean,
    role: String,
    sharedFolders: { type: Array, required: true },
});


const checkedValidity = ref(null)
const checkedSharedFolder = ref(null)

const validityList = ref([])
const searchValue = ref('')

const modal = ref(null)
const form = useForm({
    year: null,
    name: null,
})

onMounted(() => {
    validityList.value = props.validities;
});
// onUpdated(() => {
//     if (searchValue.value.length === 0) {
//         validityList.value = props.validities.sort((a, b) => a.year - b.year);
//     }
// });

// watch(searchValue, (newValue, oldValue) => {
//     if (newValue.length > 0) {
//         validityList.value = props.validities.filter((validity) => validity.year == searchValue.value);
//     } else {
//         validityList.value = props.validities.sort((a, b) => a.year - b.year);
//     }
// })


const saveValidity = () => {
    form.post(route(checkedSharedFolder.value.checked ? 'shared.folder.store' : 'validity.store'), {
        onSuccess: () => showSuccessMessage()
    })
}

const showSuccessMessage = () => {
    const flashMessage = usePage().props.value.flash.message
    const errorMessage = usePage().props.value.flash.errorMessage

    if (flashMessage) {
        CustomAlertsService.successConfirmAlert({
            title: flashMessage,
        })
    } else if (errorMessage) {
        CustomAlertsService.generalAlert({
            title: 'Error',
            text: errorMessage,
            icon: "error",
            isToast: true,
        })
    }
    closeModal()
}

const closeModal = () => {
    const modalBootstrap = bootstrap.Modal.getInstance(modal.value)
    modalBootstrap.hide()
}

const onClicks = (event) => {
    event.preventDefault();
}

const changeInputCheck = (e) => {
    const inputsValidity = document.getElementsByClassName("inputs-validity")
    const inputsSharedFolder = document.getElementsByClassName("inputs-shared-folder")
    if (e.target.value == "validity") {
        changeDisplayCheckBox(inputsValidity, "block")
        changeDisplayCheckBox(inputsSharedFolder, "none")
    } else {
        changeDisplayCheckBox(inputsSharedFolder, "block")
        changeDisplayCheckBox(inputsValidity, "none")
    }
}

const changeDisplayCheckBox = (inputs, display) => {
    Array.from(inputs).forEach(function (element) {
        element.style.display = display;
    });
}


</script>

<template>
    <div class="p-5 relative">
        <!-- TODO -->
        <!-- Buscador -->
        <div class="absolute top-3 right-12">
            <div class="flex pl-1 pr-3 py-2 rounded-2xl border-1 border-gray-300">
                <Icon name="search" />
                <input type="text" v-model="searchValue" style="all: unset" placeholder="Buscar">
                <button>
                    <Link :href="route('validity.projects', { 'validityYear': 2020 })">
                        <Icon name="send" />
                    </Link>
                </button>
            </div>
        </div>

        <div class="pt-4 row row-cols-2 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 row-cols-xxl-5 g-5">

            <button v-if="props.role == 'admin' && props.isAuthenticated" class="col" data-bs-toggle="modal"
                data-bs-target="#modalSaveValidity">
                <div class="folder border-3 rounded-4 py-3 bg-white" style="box-shadow: 5px 5px 10px 2px rgba(0, 0, 0, 0.07);">
                    <svg xmlns="http://www.w3.org/2000/svg" height="84px" viewBox="0 -960 960 960" width="84px"
                        fill="#000000">
                        <path
                            d="M453-280h60v-166h167v-60H513v-174h-60v174H280v60h173v166Zm27.27 200q-82.74 0-155.5-31.5Q252-143 197.5-197.5t-86-127.34Q80-397.68 80-480.5t31.5-155.66Q143-709 197.5-763t127.34-85.5Q397.68-880 480.5-880t155.66 31.5Q709-817 763-763t85.5 127Q880-563 880-480.27q0 82.74-31.5 155.5Q817-252 763-197.68q-54 54.31-127 86Q563-80 480.27-80Z" />
                    </svg>
                    <h6 class="py-1 text-gray-400">
                        <strong>Nueva</strong>
                    </h6>
                </div>
            </button>

            <div class="mt-auto mb-auto" v-if="validityList.length < 1">
                <h1>No hay vigencias</h1>
            </div>

            <div v-for="v in validityList" :key="v.id">
                <Card :id="v.id" :year="v.year" />
            </div>

            <div v-for="sf in props.sharedFolders" :sf="sf.id">
                <SharedFolderCard :id="sf.id" :name="sf.name" />
            </div>
        </div>
    </div>


    <!-- Modal -->


    <div ref="modal" class="modal fade" id="modalSaveValidity" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" style="width: 350px; height: 600px;">
            <div class="modal-content position-relative p-3" style="max-height: 400px;">
                <div class="d-flex flex-row justify-center px-3">
                    <h4 class="my-3" style="color: #39A900;"><strong>Nueva</strong></h4>

                    <button type="button" class="btn-close position-absolute top-1 end-3" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body px-3">
                    <form @submit.prevent="saveValidity">
                        <div class="flex mb-3">
                            <div class="form-check mr-4">
                                <input :onclick="changeInputCheck" ref="checkedValidity" class="form-check-input"
                                    type="radio" name="input-check" id="validity" value="validity" checked>
                                <label class="form-check-label" for="validity">
                                    Vigencia
                                </label>
                            </div>
                            <div class="form-check">
                                <input :onclick="changeInputCheck" ref="checkedSharedFolder" class="form-check-input"
                                    type="radio" name="input-check" value="shared-folder" id="shared-folder">
                                <label class="form-check-label" for="shared-folder">
                                    Recurso compartido
                                </label>
                            </div>
                        </div>
                        <div class="mb-3 inputs-validity">
                            <label for="year" class="form-label">Ingrese el año:</label>
                            <input v-model="form.year" type="number" class="form-control" id="year">
                            <div v-if="form.errors.year">{{ form.errors.year }}</div>
                        </div>
                        <div class="mb-3 inputs-shared-folder" style="display: none;">
                            <label for="name" class="form-label">Ingrese el nombre:</label>
                            <input v-model="form.name" type="text" class="form-control" id="name">
                            <div v-if="form.errors.name">{{ form.errors.name }}</div>
                        </div>
                        <div class="row justify-center p-3 mt-5">
                            <button type="submit" class="btn py-2"
                                style="background-color: #39A900; color: white; "><strong>Crear</strong></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


</template>

<style scoped>
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