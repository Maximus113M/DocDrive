<template>
  <div v-if="isShowing" class="fixed inset-0 z-40" @click="isShowing = !isShowing"></div>

  <button type="button" @click="isShowing = !isShowing" class="position-relative">
    <slot />

    <div v-if="isShowing" to="#dropdown">

      <div ref="dropdown" style="position: absolute; right: 5px; z-index: 99999" @click.stop="isShowing = !autoClose">
        <slot name="dropdown" />
      </div>
    </div>

  </button>
</template>


<script setup>
import { onMounted, onUnmounted, ref } from 'vue';

const props = defineProps({
  placement: {
    type: String,
    default: 'bottom-end',
  },
  autoClose: {
    type: Boolean,
    default: true,
  },
},)

const isShowing = ref(false);
onMounted(() => document.addEventListener('keydown', closeOnEscape));
onUnmounted(() => document.removeEventListener('keydown', closeOnEscape));

const closeOnEscape = (e) => {
  if (isShowing.value && e.key === 'Escape') {
    isShowing.value = false;
  }
};

</script>
