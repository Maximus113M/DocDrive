<script setup>
import AuthLayout from '@/Layouts/Authenticated.vue';
import ContentIndex from '@/Pages/Search/components/ContentIndex.vue';
import { onMounted } from 'vue';


const props = defineProps({
    projects: { type: Array, required: true },
    documents: { type: Array, required: true },
    folders: { type: Array, required: true },
    validities: { type: Array, required: true },
    sharedResources: { type: Array, required: true },
    isAuthenticated: { type: Boolean, required: true },
    role: { type: String, required: true },
    visualizationsRole: { type: Array, required: true },
    documentsForCategory: { type: Array, required: true },
    documentCategories: { type: Array, required: true },
    query: { type: String, required: true },
})


</script>

<template>

    <AuthLayout :role="props.role">
            <ContentIndex :documentCategories="props.documentCategories" :projects="props.projects" :documents="props.documents" :folders="props.folders"
                :validities="props.validities" :sharedResources="props.sharedResources"
                :isAuthenticated="props.isAuthenticated" :role="props.role" :visualizations-role="visualizationsRole" 
                :documents-for-category="props.documentsForCategory" :query="props.query" #content />
    </AuthLayout>

</template>
