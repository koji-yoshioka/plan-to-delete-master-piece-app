<template>
  <Section class="page-profile-section">
    <h2 class="page-profile-title">EX会社情報編集</h2>
  </Section>
</template>




<script lang="ts">
import { onMounted } from 'vue'
import useVuelidate from '@vuelidate/core'
import { required } from '@vuelidate/validators'
import Section from '@/components/Section.vue'

export default {
  components: {
    Section
  },
  setup() {
    return { v$: useVuelidate() }
  },
  data() {
    return {
      name: 'aaaaaaaaaa'
    }
  },
  validations() {
    return {
      name: { required }
    }
  }
}
</script>

<style lang="scss" scoped>
@use "~@/variables";
@use "~@/mixins";
.page-profile-section {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin: 0 auto;
  row-gap: 30px;
  width: clamp(200px, 80%, 500px);
  // @include mixins.mq(sp) {
  // }
  // @include mixins.mq(tablet) {
  // }
  @include mixins.mq(pc) {
    row-gap: 40px;
  }
}
.page-profile-title {
  color: variables.$font-color;
  @include mixins.mq(sp) {
    font-size: 20px;
  }
  // @include mixins.mq(tablet) {
  // }
  @include mixins.mq(pc) {
    font-size: 36px;
  }
}
.page-profile-logo-width {
  width: clamp(200px, 50%, 300px);
}
.page-profile-image-width {
  width: 100%;
}

.page-profile-zip-search-area {
  width: 100%;
  &__label {
    color: variables.$font-color;
    display: block;
    margin-bottom: 10px;
  }
  &__input {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    width: 100%;
  }
  &__text {
    width: 45%;
  }
  &__button {
    width: 45%;
  }
}

.page-profile-sub-image-area {
  width: 100%;
  &__label {
    color: variables.$font-color;
    display: block;
    margin-bottom: 10px;
  }
  &__files {
    display: flex;
    justify-content: space-between;
    width: 100%;
  }
  &__file {
    width: 30%;
  }
}
.page-profile-submit-button {
  @include mixins.mq(sp) {
    width: 100%;
  }
  @include mixins.mq(tablet) {
    max-width: 250px;
    width: 100%;
  }
  @include mixins.mq(pc) {
    max-width: 250px;
    width: 100%;
  }
}
</style>
