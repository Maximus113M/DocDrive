<template>
    <div class="w-full h-fit p-5 relative">
        <!-- Buscador -->
        <div class="w-full absolute left-0 top-5 flex-wrap sm:flex justify-between px-4">

            <div class="flex">
                <div class="col-1 mr-5">
                    <Link method="get" :href="route('validity.index')">
                    <Icon name="back" />
                    </Link>
                </div>
                <div class="text-xl font-bold">
                    Resultados
                </div>
                <div class="text-xl font-bold text-gray-600 ml-2">
                    {{ `"${query}"` }}
                </div>

            </div>
            <!-- TODO HACER ALGUN DIA EL BUSCADOR :v -->
            <!-- <div class="flex pl-1 pr-3 py-2 rounded-2xl border-1 border-gray-300">
                <Icon name="search" />
                <input type="text" v-model="searchValue" style="all: unset" placeholder="Buscar">
            </div> -->
        </div>

        <div class="w-full h-full flex justify-center items-center"
            v-if="props.validities.length < 1 && props.sharedResources.length < 1 &&
                props.projects.length < 1 && props.folders.length < 1 && props.documents.length < 1 && props.documentsForCategory < 1">
            <h2>No se encontraron coincidencias</h2>
        </div>

        <div>
            <div v-if="props.validities.length > 0" class="my-3">
                <div class="text-xl font-bold mb-2">Vigencias</div>
                <div class="pt-4 row row-cols-2 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 row-cols-xxl-5 g-5">
                    <div v-for="v in props.validities" :key="v.id">
                        <ValidityCard :id="v.id" :year="v.year" />
                    </div>
                </div>
            </div>
            <div v-if="props.sharedResources.length > 0" class="my-3">
                <div class="text-xl font-bold mb-2">Recursos compartidos</div>
                <div class="row row-cols-2 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 row-cols-xxl-5 g-5">
                    <div v-for="sf in props.sharedResources" :key="sf.id">
                        <SharedFolderCard :id="sf.id" :name="sf.name" />
                    </div>
                </div>
            </div>
            <div v-if="props.projects.length > 0" class="my-3">
                <div class="text-xl font-bold mb-2">Proyectos</div>
                <div class="row row-cols-2 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 row-cols-xxl-5 g-5">
                    <div v-for="project in props.projects" :key="project.id">
                        <CardProject :visualizationsRole="props.visualizationsRole" :project="project"
                            :current-year="project.startDate.split('-')[0]" />
                    </div>
                </div>
            </div>
            <div v-if="props.folders.length > 0" class="my-3">
                <div class="text-xl font-bold mb-2">Carpetas</div>
                <div class="row row-cols-2 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 row-cols-xxl-5 g-5">
                    <div v-for="folder in props.folders" :key="folder.id">
                        <CardProject :visualizationsRole="props.visualizationsRole" :project="folder.project"
                            :folder="folder" :current-year="folder.project.startDate.split('-')[0]" :is-folder="true" />
                    </div>
                </div>
            </div>
            <div v-if="props.documents.length > 0" class="my-3">
                <div class="text-xl font-bold mb-2">Documentos</div>
                <div class="row row-cols-2 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 row-cols-xxl-5 g-5">
                    <div v-for="document in props.documents" :key="document.id">
                        <CardDocumentDetails :visualizations-role="props.visualizationsRole" :currentYear="document.project ? Number(document.project.startDate.split('-')[0])
                            : Number(document.folder[0].project.startDate.split('-')[0])" :document="document"
                            :project="document.project ? document.project : document.folder[0].project"
                            :document-categories="categoryList" />
                    </div>
                </div>
            </div>

            <div v-if="props.documentsForCategory.length > 0" class="my-3">
                <div class="text-xl font-bold mb-2">Categorias</div>
                <div class="row row-cols-2 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 row-cols-xxl-5 g-5">
                    <div v-for="document in props.documentsForCategory" :key="document.id">
                        <CardDocumentDetails :visualizations-role="props.visualizationsRole" :currentYear="document.project ? Number(document.project.startDate.split('-')[0])
                            : Number(document.folder[0].project.startDate.split('-')[0])" :document="document"
                            :project="document.project ? document.project : document.folder[0].project"
                            :document-categories="categoryList" />
                    </div>
                </div>
            </div>

        </div>
    </div>

</template>

<script setup>
import ValidityCard from '@/Pages/Validity/Components/CardValidity.vue';
import SharedFolderCard from '../../SharedFolder/Components/CardSharedFolder.vue'
import CardProject from '@/Pages/Projects/Components/CardProject.vue';
import CardDocumentDetails from '@/Pages/Projects/Project/Components/CardDocumentDetails.vue';
import Icon from '@/Shared/Icon.vue';
import { CustomAlertsService } from '@/services/customAlerts';
import { Link, useForm, usePage } from '@inertiajs/inertia-vue3'
import { ref, onUpdated, onMounted, watch } from 'vue';

const props = defineProps({
    projects: { type: Array, required: true },
    documents: { type: Array, required: true },
    folders: { type: Array, required: true },
    validities: { type: Array, required: true },
    sharedResources: { type: Array, required: true },
    isAuthenticated: Boolean,
    role: String,
    visualizationsRole: { type: Array, required: true },
    documentsForCategory: { type: Array, required: true },
    documentCategories: { type: Array, required: true },
    query: { type: String, required: true },
});

const checkedValidity = ref(null)
const checkedSharedFolder = ref(null)

const validityList = ref([])
const searchValue = ref('')

const modal = ref(null)
const form = useForm({
    year: null,
    name: null,
});

const query = ref('');

onMounted(() => {
    validityList.value = props.validities;

    console.log('INICIO DE BUSQUEDA')
    console.log(props.documentsForCategory)
    console.log(props.documentCategories)
    if (props.query) {
        query.value = props.query.replace(' ', '+')
        console.log(query.value)
    }

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

const categoryList = ref([...props.documentCategories]);


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