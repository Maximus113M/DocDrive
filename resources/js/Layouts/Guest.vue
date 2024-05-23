<script setup>
import NavMenu from '@/Components/SideMenu.vue'


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
