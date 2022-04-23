<script setup lang="ts">
import Section from '@/components/Section.vue'
import Japan from '@/components/Japan.vue'
import KantoArea from '@/components/KantoArea.vue'
</script>

<template>
  <div class="page-search">
    <Section class="page-search__area">
      <component :is="Japan"></component>
    </Section>
  </div>
</template>

<style lang="scss" scoped>
@use "~@/variables";
@use "~@/mixins";
.page-search {
  align-items: center;
  display: flex;
  flex-direction: column;
  &__area {
    max-width: 860px;
    width: 100%;
    @include mixins.mq(sp) {
      padding-left: 10px;
      padding-right: 10px;
    }
  }
}
</style>
