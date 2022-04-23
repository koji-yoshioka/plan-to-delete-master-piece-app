<template>
  <table class="c-vertical-table">
    <slot></slot>
  </table>
</template>

<style lang="scss" scoped>
@use "~@/variables";
.c-vertical-table {
  border: variables.$border-color 2px solid;
  border-collapse: collapse;
  width: 100%;
}
</style>
