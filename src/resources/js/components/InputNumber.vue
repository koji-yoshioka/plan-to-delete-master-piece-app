<script setup lang="ts">
import { PropType, ref } from 'vue'
import { ErrorObject } from '@vuelidate/core'

const showError = ref(false)

const props = defineProps({
  maxlength: {
    type: Number,
    default: null,
  },
  placeholder: {
    type: String,
    defautl: null,
  },
  errors: {
    type: Array as PropType<Array<ErrorObject>>,
    default: (): Array<ErrorObject> => [],
  },
})

const emit = defineEmits(['update:modelValue'])

const onInput = (event: Event) => {
  showError.value = false
  const target = event.target as HTMLInputElement;
  emit("update:modelValue", target.value)
}

const onBlur = (event: Event) => {
  // フォーカスが外れたタイミングでエラーを表示する
  showError.value = props.errors.length > 0
}

</script>

<template>
  <div class="c-input-number">
    <input class="c-input-number__number" :class="{ 'has-error': showError }" type="number" :maxlength="maxlength"
      :placeholder="placeholder" :value="$attrs.modelValue" @input="onInput" @blur="onBlur" />
    <template v-if="showError" v-for="error of errors" :key="error.$uid">
      <p class="c-input-number__error-msg">{{ error.$message }}</p>
    </template>
  </div>
</template>

<style lang="scss" scoped>
@use "~@/variables";

.c-input-number {
  width: 100%;

  &__number {
    background-color: #fff;
    border: variables.$border-color 2px solid;
    border-radius: 3px;
    height: 50px;
    padding-left: 10px;
    padding-right: 10px;
    width: 100%;

    &:focus {
      border: #1967d2 2px solid;
    }
  }

  &__number.has-error {
    background-color: #ffd9d9;
    border: variables.$border-color 2px solid;
    border-radius: 3px;
    height: 50px;
    padding-left: 10px;
    padding-right: 10px;
    width: 100%;

    &:focus {
      border: #1967d2 2px solid;
    }
  }

  &__error-msg {
    color: #ff0000;
    margin-top: 10px;
  }
}
</style>

