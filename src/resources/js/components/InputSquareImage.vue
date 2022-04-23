<script setup lang="ts">
import { PropType } from 'vue'

const props = defineProps({
  accept: {
    type: Array as PropType<string[]>,
    default: ['image/*'],
  },
  preview: {
    type: String as PropType<string | ArrayBuffer | null>,
    default: null,
  },
});

const emit = defineEmits<{
  (e: 'upload', value: File): void
  (e: 'fileDelete'): void
}>()

const onFileChange = (event: Event) => {
  let files = null
  if (event instanceof DragEvent) {
    // ドラッグ＆ドロップされた場合
    files = event.dataTransfer ? event.dataTransfer.files : null
  } else {
    // ファイル選択された場合
    const target = event.target as HTMLInputElement
    files = target.files ? target.files : null
  }

  if (!files || files.length === 0) {
    return
  }
  const targetImage = files[0]
  const reader = new FileReader()
  reader.readAsDataURL(targetImage)
  emit('upload', targetImage)
}

const onClick = (event: Event) => {
  // 1つ前の画像情報が残っている可能性があるため、初期化
  const target = event.target as HTMLInputElement
  target.value = ''
}

const onFileDelete = (event: Event) => {
  emit('fileDelete')
}
</script>

<template>
  <div class="input-file">
    <font-awesome-icon v-show="preview" :icon="['far', 'circle-xmark']" size="2x" class="input-file__delete-icon"
      @click.stop="onFileDelete" />
    <label class="input-file__drag-drop-area" @dragenter.prevent @dragover.prevent @drop.prevent="onFileChange">
      <input :accept="accept.join()" class="input-file__file" type="file" @click="onClick" @change="onFileChange" />
      <span class="input-file__text">
        <slot v-if="!preview">未選択</slot>
      </span>
      <img v-show="preview" :src="preview" class="input-file__preview" />
    </label>
  </div>
</template>

<style lang="scss" scoped>
@use "~@/variables";
@use "~@/mixins";

.input-file {
  position: relative;
  width: 100%;

  &__drag-drop-area {
    background-color: #fff;
    border: variables.$border-color 2px solid;
    border-radius: 3px;
    display: block;
    position: relative;
    width: 100%;

    &::before {
      content: "";
      display: block;
      padding-bottom: 100%;
    }

    &:focus-within {
      border: #1967d2 2px solid;
    }

    &:hover {
      cursor: pointer;
    }
  }

  &__text {
    color: #ccc;
    font-weight: 700;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
  }

  &__file {
    left: 0;
    opacity: 0;
    position: absolute;
    top: 0;
    width: 100%;
  }

  &__delete-icon {
    background-color: #fff;
    border-radius: 50%;
    position: absolute;
    right: 1%;
    top: 1%;
    z-index: 1;
  }

  &__preview {
    height: 100%;
    left: 0;
    object-fit: cover;
    position: absolute;
    top: 0;
    width: 100%;
  }
}
</style>
