<template>
    <div ref="modal" class="modal fade" :id="modalId" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" style="width: 400px;">
            <div class="modal-content position-relative p-3" style="max-height: 600px;">
                <div class="d-flex flex-row justify-center px-3">
                    <h4 class="my-3" :style="`color: ${dialogTheme}`">
                        <strong> Editar Categoría </strong>
                    </h4>

                    <button type="button" class="btn-close position-absolute top-2 end-2" aria-label="Close"
                        @click="closeModal"></button>
                </div>

                <div class="modal-body px-3">
                    <form @submit.prevent="editCategory">
                        <div class="mb-3">
                            <label for="name" class="font-bold">Nombre</label>
                            <input v-model="form.name" type="text" class="form-control" id="name" >
                            <div class="text-red-400" v-if="form.errors.name">{{ AppFunctions.getErrorTranslate(AppFunctions.Errors.Field) }}</div>
                        </div>
                        
                        <div class="row justify-center p-3 mt-5">
                            <button :disabled="form.processing" type="submit" class="btn py-2 text-white"
                                :style="`background-color: ${dialogTheme}`">
                                <strong>Actualizar</strong>
                            </button>
                        </div>
                    </form>
                </div>
                
            </div>
        </div>
    </div>
</template>

<script setup>
import { onBeforeUpdate, ref } from "vue";
import { useForm } from '@inertiajs/inertia-vue3';
import { CustomAlertsService } from "@/services/customAlerts";
import { AppFunctions } from "@/core/appFunctions";

const props = defineProps({
    modalId: { type: String, required: true },
    currentCategory: Object,
    isShowing: { type: Boolean, required: true },

});
const dialogTheme = '#39A900';

const modal = ref(null);

const form = useForm({
    name: null,
});

const emit= defineEmits(["loadedModal"])

onBeforeUpdate(() => { 
    if (props.currentCategory && props.isShowing) {
        form.name = props.currentCategory.name;

        emit("loadedModal", true);
    }
})

const editCategory = () => {
    form.put(route('document-category.update', { 'categoryID': props.currentCategory.id }), {
        onSuccess: () => {
            closeModal();
            CustomAlertsService.generalAlert({
                title: 'Actualización exitosa',
                text: `La categoría ha sido actualizada exitosamente`,
                isToast: true,
            })
        },
        onError: () => {
            CustomAlertsService.generalAlert({
                title: 'Error',
                text: `Ha ocurrido un error al actualizar la categoría seleccionada`,
                icon: "error",
                isToast: true,
            })
        }
    })
}

const closeModal = () => {
    form.clearErrors();
    form.reset();
    const modalBootstrap = bootstrap.Modal.getInstance(modal.value);
    modalBootstrap.hide();
}

</script>