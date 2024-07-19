<script setup>
import NavMenu from '@/Components/SideMenu.vue'
import MainMenu from '@/Shared/MainMenu.vue';

//TAL VEZ DEBERIA SER SOLO UN PUNTO DE INGRESO, MEJOR VALIDAR QUE ES VISIBLE SI ES VISITANTE
</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100">
            
            <NavMenu :isGuest="true"  :role="role"/>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>

    </div>
</template>
