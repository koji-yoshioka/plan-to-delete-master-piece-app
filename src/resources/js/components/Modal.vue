<script setup lang="ts">
import { onMounted, PropType, ref } from 'vue'

const props = defineProps({
  title: {
    type: String as PropType<String | null>,
    default: null,
  },
  showButton: {
    type: Boolean as PropType<boolean>,
    default: false,
  },
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
  <div @click.stop class="c-modal">
    <div class="c-modal__header">{{ title }}</div>
    <div class="c-modal__content">
      <slot></slot>
    </div>
    <div v-if="showButton" class="c-modal__button-area">
      <!-- <button class="c-modal__button is-positive" @click.stop="showModal = false">OK</button> -->
      <button class="c-modal__button is-negative" @click.stop="close($event)">キャンセル</button>
    </div>
  </div>
</template>

<style lang="scss" scoped>
@use "~@/variables";

.c-modal {
  background-color: #eee;
  border: variables.$border-color 2px solid;
  display: flex;
  flex-direction: column;
  height: 300px;
  width: 300px;
  position: fixed;
  inset: 0;
  margin: auto;

  &__header {
    background-color: #1c1c1c;
    color: variables.$font-color;
    padding: 10px;
  }

  &__content {
    flex-grow: 1;
    overflow: auto;
    padding: 10px;
  }

  &__button-area {
    display: flex;
    gap: 20px;
    justify-content: center;
    padding: 10px;
  }

  &__button {
    border: 2px solid black;
    border-radius: 10px;
    padding: 5px;
    text-align: center;
  }
}
</style>

