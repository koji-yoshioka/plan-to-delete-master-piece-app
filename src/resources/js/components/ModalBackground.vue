<script setup lang="ts">
import { onMounted, PropType, ref } from 'vue'

const props = defineProps({
  show: {
    type: Boolean as PropType<boolean>,
    default: false,
  },
})
const emit = defineEmits<{
  (e: 'close'): void
}>()

const bodyHeight = ref<number>(0)

const close = (event: Event) => {
  emit('close')
}

onMounted(() => {
  bodyHeight.value = document.body.scrollHeight
})
</script>

<template>
  <teleport to="body">
    <div v-if="show" @click.stop="close" class="c-modal-base" :style="'height:' + bodyHeight + 'px'">
      <slot></slot>
    </div>
  </teleport>
</template>

<style lang="scss" scoped>
.c-modal-base {
  align-items: center;
  background-color: rgba(0, 0, 0, 0.5);
  bottom: 0;
  display: flex;
  flex-direction: column;
  justify-content: center;
  left: 0;
  position: absolute;
  right: 0;
  top: 0;
  z-index: 3;
}
</style>
