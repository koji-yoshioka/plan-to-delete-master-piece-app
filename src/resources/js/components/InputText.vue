<script setup lang="ts">
import { PropType, ref } from 'vue'
import { ErrorObject } from '@vuelidate/core'

const showError = ref(false)

const props = defineProps({
  // label: {
  //   type: String,
  //   defautl: null,
  // },
  maxlength: {
    type: Number,
    default: null,
  },
  placeholder: {
    type: String,
    defautl: null,
  },
  // withRequiredLabel: {
  //   type: Boolean,
  //   default: false,
  // },
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
  showError.value = props.errors.length > 0 ? true : false
}

</script>

<template>
  <div class="c-input-text">
    <!-- <label v-if="label" class="c-input-text__label">{{ label }}</label>
    <label v-if="withRequiredLabel" class="c-input-text__label--required">必須</label>-->
    <input
      class="c-input-text__text"
      :class="{ 'has-error': showError }"
      type="text"
      :maxlength="maxlength"
      :placeholder="placeholder"
      :value="$attrs.modelValue"
      @input="onInput"
      @blur="onBlur"
    />
    <template v-if="showError" v-for="error of errors" :key="error.$uid">
      <p class="c-input-text__error-msg">{{ error.$message }}</p>
    </template>
  </div>
</template>

<style lang="scss" scoped>
@use "~@/variables";
.c-input-text {
  width: 100%;
  // &__label {
  //   color: variables.$font-color;
  //   display: inline-block;
  //   margin-bottom: 10px;
  // }
  // &__label--required {
  //   background-color: #ff0000;
  //   border-radius: 10px;
  //   color: #fff;
  //   display: inline-block;
  //   padding: 5px 10px;
  //   margin-left: 10px;
  // }
  &__text {
    background-color: #eee;
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
  &__text.has-error {
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

