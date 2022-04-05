<script setup lang="ts">
const emit = defineEmits(['click'])
const onClick = () => {
  emit('click')
}
</script>

<template>
  <button class="primary-button" @click="onClick">
    <slot></slot>
  </button>
</template>

<style lang="scss" scoped>
@use "~@/variables";
.primary-button {
  background-color: variables.$primary-btn-bg-color;
  border: 2px solid variables.$primary-btn-border-color;
  border-radius: 3px;
  color: variables.$primary-btn-font-color;
  // line-height: 46px;
  height: 50px;
  letter-spacing: 0.1em;
  padding-left: 20px;
  padding-right: 20px;
  text-align: center;
  transition: all 0.4s ease;
  width: 100%;
  &:focus {
    border: #1967d2 2px solid;
  }
  &:hover {
    background-color: variables.$primary-btn-bg-color--hover;
    border: 2px solid variables.$primary-btn-border-color--hover;
    color: variables.$primary-btn-font-color--hover;
    // transform: translateY(-2px);
  }
}
</style>
