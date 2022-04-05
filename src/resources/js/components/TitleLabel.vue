<script setup lang="ts">
import { PropType, ref } from 'vue'

const props = defineProps({
  id: {
    type: String as PropType<string | null>,
    default: 'null',
  },
  required: {
    type: Boolean,
    default: false,
  },
});
</script>

<template>
  <div class="c-label">
    <label class="c-label__text">
      <slot></slot>
    </label>
    <label v-if="required" class="c-label__text--required">必須</label>
  </div>
</template>

<style lang="scss" scoped>
@use "~@/variables";
.c-label {
  &__text {
    color: variables.$font-color;
    display: inline-block;
    margin-bottom: 10px;
  }
  &__text--required {
    background-color: #ff0000;
    border-radius: 10px;
    color: #fff;
    display: inline-block;
    padding: 5px 10px;
    margin-left: 10px;
  }
}
</style>
