<template>
    <AuthLayout :role="props.role" v-if="props.isAuthenticated">
        <div class="w-full m-auto px-4">
            <div style="min-height: 500px;">

                <div class="w-full">
                    <div class="flex justify-between items-center">
                        <button type="button"
                            class="flex items-center justify-center my-4 px-3 pr-4 rounded-3xl text-white"
                            :style="`background-color: ${tableTheme}`" data-bs-toggle="modal"
                            :data-bs-target="'#modalSaveCategory'">
                            <Icon name="add" class="w-6 pr-1" />
                            Categoría
                        </button>

                        <div class="flex pl-1 pr-3 py-2 rounded-2xl border-1 border-gray-300">
                            <Icon name="search" />
                            <input type="text" v-model="searchValue" style="all: unset" placeholder="Buscar">
                        </div>
                    </div>
                </div>

                <!-- TABLE -->
                <div class="card p-3 rounded-3xl">
                    <Vue3EasyDataTable :headers="headers" :items="categories" :search-field="searchFields"
                        :search-value="searchValue" :rows-items="[10, 15, 25, 50]" :rows-per-page="10"
                        rows-per-page-message="Filas por página" empty-message="No se encontraron resultados"
                        rows-of-page-separator-message="de" :theme-color="tableTheme" buttons-pagination>

                        <template #item-actions="item">
                            <div class="flex justify-start items-center">
                                <div>
                                    <Icon name="edit" @click="selectedCategoryToEdit(item)" class="pr-4"
                                        data-bs-toggle="modal" :data-bs-target="`#categoryModal`" />
                                </div>
                                <div data-bs-toggle="tooltip" data-bs-placement="bottom" title="Eliminar">
                                    <Icon name="delete" @click="showDeleteConfirm(item.id)" />
                                </div>
                            </div>
                        </template>
                    </Vue3EasyDataTable>
                </div>

                <EditCategoryModal :modal-id="'categoryModal'" :current-category="currentCategory"
                    :is-showing="isShowingModal" @loaded-modal="changeVisibilityModalStatus" />

            </div>
        </div>
    </AuthLayout>
    <!-- CreateModal -->
    <CreateDocumentCategory modal-id="modalSaveCategory" />

</template>

<script setup>
import Icon from '@/Shared/Icon.vue';
import AuthLayout from '@/Layouts/Authenticated.vue';
import EditCategoryModal from '@/Pages/DocumentCategories/Components/EditDocumentCategory.vue';
import CreateDocumentCategory from '@/Pages/DocumentCategories/Components/CreateDocumentCategory.vue';
import Vue3EasyDataTable from 'vue3-easy-data-table';
import 'vue3-easy-data-table/dist/style.css';
import { useForm } from '@inertiajs/inertia-vue3';
import { onBeforeMount, onMounted, ref } from 'vue';
import { CustomAlertsService } from '@/services/customAlerts';

const props = defineProps({
    categories: { type: Array, required: true },
    isAuthenticated: Boolean,
    role: String,
})

window.onresize = function () {
    columnWidth.value = (window.innerWidth - 400) / 3;
    if (window.innerWidth > 850) {
        actionsWidth.value = columnWidth.value - 100;
    } else {
        actionsWidth.value = columnWidth.value;
    }
    headers.value = [
        { text: "ID", value: "id", sortable: true, width: columnWidth.value },
        { text: "NOMBRE", value: "name", sortable: true, width: columnWidth.value },
        { text: "ACCIONES", value: "actions", width: actionsWidth.value },
    ];
};

onBeforeMount(()=>{
    columnWidth.value = (window.innerWidth - 300) / 3;
    debugger
    if (window.innerWidth > 850) {
        actionsWidth.value = columnWidth.value - 100;
    } else {
        actionsWidth.value = columnWidth.value;
    }
})

const columnWidth = ref((window.innerWidth - 300) / 3);
const actionsWidth = ref(window.innerWidth > 850? columnWidth.value -100 : columnWidth.value);

const tableTheme = '#39A900';
const searchValue = ref('')
const searchFields = ["id", "name"];

const currentCategory = ref(undefined);
const isShowingModal = ref(false);

const changeVisibilityModalStatus = (value) => {
    isShowingModal.value = !value;
}

const selectedCategoryToEdit = (category) => {
    console.log(window.screen.width)
    isShowingModal.value = true;
    currentCategory.value = { id: category.id, name: category.name };
}

const showDeleteConfirm = (categoryId) => {
    if(categoryId === 1){
        CustomAlertsService.generalAlert({
                title: 'Eliminación invalida',
                text: `Esta categoría no puede ser eliminada`,
                isToast: true,
                icon: 'warning'
            });
        return;
    }
    CustomAlertsService.deleteConfirmAlert({
        title: 'Eliminar categoría',
        text: '¿Deseas eliminar la categoría seleccionada? Esta acción no se puede revertir'
    }).then((result) => {
        if (result.isConfirmed) {
            useForm().delete(route('document-category.destroy', { 'categoryID': categoryId }), {
                onSuccess: () => {
                    CustomAlertsService.generalAlert({
                        title: 'Categoría Eliminada',
                        text: `La categoría ha sido eliminada exitosamente`,
                        isToast: true,
                        showTimer: true,
                        position: "top-end"
                    })
                },
                onError: () => {
                    CustomAlertsService.generalAlert({
                        title: 'Error',
                        text: `Ha ocurrido un error al eliminar la categoría`,
                        icon: "error",
                        isToast: true,
                    })
                }
            })
        }
    }
    )
}

const headers = ref([
    { text: "ID", value: "id", sortable: true, width: columnWidth.value },
    { text: "NOMBRE", value: "name", sortable: true, width: columnWidth.value },
    { text: "ACCIONES", value: "actions", width: actionsWidth.value },
]);

</script>