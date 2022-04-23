<script setup lang="ts">
import { PropType } from 'vue'

const props = defineProps({
  columnName: {
    type: String as PropType<string>,
    default: null,
  },
});
</script>

<template>
  <tr class="c-vertical-table-row">
    <th class="c-vertical-table-row__header">{{ columnName }}</th>
    <td class="c-vertical-table-row__data">
      <slot></slot>
    </td>
  </tr>
</template>

<style lang="scss" scoped>
@use "~@/variables";
@use "~@/mixins";

.c-vertical-table-row {
  width: 100%;

  &__header {
    width: 100%;
    border: variables.$border-color 2px solid;
    color: variables.$font-color;
    vertical-align: middle;

    @include mixins.mq(sp) {
      padding: 10px;
      width: 30%;
    }

    @include mixins.mq(tablet, pc) {
      padding: 20px;
      width: 20%;
    }
  }

  &__data {
    background-color: #fff;
    border: variables.$border-color 2px solid;
    vertical-align: middle;

    @include mixins.mq(sp) {
      padding: 10px;
    }

    @include mixins.mq(tablet, pc) {
      padding: 20px;
    }
  }
}
</style>
