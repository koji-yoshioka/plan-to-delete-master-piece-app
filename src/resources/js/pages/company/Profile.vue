<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { useVuelidate, ValidationArgs } from '@vuelidate/core'
import { required, email, maxLength, helpers } from '@vuelidate/validators'
import axios from 'axios'
import Section from '@/components/Section.vue'

import TitleLabel from '@/components/TitleLabel.vue'

import InputSquareImage from '@/components/InputSquareImage.vue'
import InputText from '@/components/InputText.vue'
import InputTel from '@/components/InputTel.vue'
import InputEMail from '@/components/InputEMail.vue'
import PrimaryButton from '@/components/PrimaryButton.vue'

const plainFields = ref<ValidationArgs>({
  id: null,
  name: null,
  email: null,
  tel: null,
  zipCode: null,
  prefecture: null,
  city: null,
  restAddress: null,
})

const rules = {
  id: { required },
  name: {
    required: helpers.withMessage('会社名は必須です。', required), maxLength: maxLength(255),
  },
  email: { required: helpers.withMessage('メールアドレスは必須です。', required), email: helpers.withMessage('メールアドレスの形式が不正です。', email) },
  tel: {},
  zipCode: { zipCode: helpers.withMessage('郵便番号は7桁の整数で入力してください。', (value: string | null) => !value || /^[0-9]{7}$/.test(value)) },
  prefecture: {},
  city: {},
  restAddress: {},
}
const v$ = useVuelidate(rules, plainFields)

const uploadLogo = ref<{
  logo: File | null
  preview: string | ArrayBuffer | null
  isChanged: boolean
}>({
  logo: null,
  preview: null,
  isChanged: false,
})

const uploadImages = ref<{
  displayNo: number
  image: File | null
  preview: string | ArrayBuffer | null
  isChanged: boolean
}[]>([
  {
    displayNo: 1,
    image: null,
    preview: null,
    isChanged: false,
  },
  {
    displayNo: 2,
    image: null,
    preview: null,
    isChanged: false,
  },
  {
    displayNo: 3,
    image: null,
    preview: null,
    isChanged: false,
  },
  {
    displayNo: 4,
    image: null,
    preview: null,
    isChanged: false,
  },
])

onMounted(
  async () => {
    const res: {
      data: {
        name: string | null
        email: string | null
        tel: string | null
        zipCode: string | null
        prefecture: string | null
        city: string | null
        restAddress: string | null
        logo: {
          url: string | ArrayBuffer | null
        },
        images: {
          displayNo: number
          url: string | ArrayBuffer | null
        }[]
      }
    } = await axios.get('/api/company/1')

    v$.value.id.$model = 1
    v$.value.name.$model = res.data.name
    v$.value.email.$model = res.data.email
    v$.value.tel.$model = res.data.tel
    v$.value.zipCode.$model = res.data.zipCode
    v$.value.prefecture.$model = res.data.prefecture
    v$.value.city.$model = res.data.city
    v$.value.restAddress.$model = res.data.restAddress
    uploadLogo.value.preview = res.data.logo.url
      ? 'https://s3-ap-northeast-1.amazonaws.com/master-piece-company-images/1/logo/' + res.data.logo.url
      : null
    uploadImages.value.map(uploadImage => {
      res.data.images.forEach(image => {
        if (image.displayNo === uploadImage.displayNo) {
          uploadImage.preview = 'https://s3-ap-northeast-1.amazonaws.com/master-piece-company-images/1/images/' + image.url
        }
        return image
      })
    })
  }
)

const onLogoChange = (logo: File) => {
  const reader = new FileReader()
  reader.onload = onloadEvent => {
    uploadLogo.value.preview = onloadEvent.target ? onloadEvent.target.result : null
  }
  reader.readAsDataURL(logo)
  uploadLogo.value.logo = logo
  uploadLogo.value.isChanged = true
}

const onImageChange = (image: File, displayNo: number) => {
  const reader = new FileReader()
  reader.onload = onloadEvent => {
    uploadImages.value.map(uploadImage => {
      if (uploadImage.displayNo === displayNo) {
        uploadImage.preview = onloadEvent.target ? onloadEvent.target.result : null
      }
      return uploadImage
    })
  }
  reader.readAsDataURL(image)
  uploadImages.value.map(uploadImage => {
    if (uploadImage.displayNo === displayNo) {
      uploadImage.image = image
      uploadImage.isChanged = true
    }
    return uploadImage
  })
}

const onLogoDelete = () => {
  uploadLogo.value.logo = null
  uploadLogo.value.preview = null
  uploadLogo.value.isChanged = true
}

const onImageDelete = (displayNo: number) => {
  uploadImages.value.map(uploadImage => {
    if (uploadImage.displayNo === displayNo) {
      uploadImage.image = null
      uploadImage.preview = null
      uploadImage.isChanged = true
    }
    return uploadImage
  })
}

const jsonpAdapter = require('axios-jsonp')
const getZipCode = async () => {
  const res: {
    data: {
      pref: string
      city: string
      town: string
    }
  } = await axios.get(`https://api.zipaddress.net/?zipcode=${v$.value.zipCode.$model}`, { adapter: jsonpAdapter })
  v$.value.prefecture.$model = res.data.pref
  v$.value.city.$model = res.data.city
  v$.value.restAddress.$model = res.data.town
}

const update = () => {
  if (v$.value.$invalid) {
    return;
  }
  const formData = new FormData()
  if (v$.value.name.$model && typeof v$.value.name.$model === 'string') {
    formData.append('name', v$.value.name.$model)
  }
  if (v$.value.email.$model && typeof v$.value.email.$model === 'string') {
    formData.append('email', v$.value.email.$model)
  }
  if (v$.value.tel.$model && typeof v$.value.tel.$model === 'string') {
    formData.append('tel', v$.value.tel.$model)
  }
  if (v$.value.zipCode.$model && typeof v$.value.zipCode.$model === 'string') {
    formData.append('zipCode', v$.value.zipCode.$model)
  }
  if (v$.value.prefecture.$model && typeof v$.value.prefecture.$model === 'string') {
    formData.append('prefecture', v$.value.prefecture.$model)
  }
  if (v$.value.city.$model && typeof v$.value.city.$model === 'string') {
    formData.append('city', v$.value.city.$model)
  }
  if (v$.value.restAddress.$model && typeof v$.value.restAddress.$model === 'string') {
    formData.append('restAddress', v$.value.restAddress.$model)
  }
  if (uploadLogo.value.isChanged) {
    console.log('logo is changed');
    formData.append('logo', uploadLogo.value.logo ? uploadLogo.value.logo : '')
  }
  uploadImages.value
    .filter(uploadImage => uploadImage.isChanged)
    .forEach(uploadImage => {
      formData.append('images[' + uploadImage.displayNo + ']', uploadImage.image ? uploadImage.image : '')
    })
  // formData.forEach((v, k, f) => {
  //   console.log('key:' + k)
  //   console.log('value', v)
  // })
  const response = axios.post(`/api/company/1`, formData, {
    headers: {
      'X-HTTP-Method-Override': 'PUT',
      'content-type': 'multipart/form-data',
    }
  })
}
</script>

<template>
  <Section id="page-profile">
    <h2 class="title">会社情報編集</h2>

    <div class="logo-area">
      <TitleLabel class="logo-area__title">ロゴ画像</TitleLabel>
      <InputSquareImage
        class="logo-area__image"
        :accept="['image/jpeg', 'image/png', 'image/svg+xml']"
        :preview="uploadLogo.preview"
        @upload="onLogoChange"
        @fileDelete="onLogoDelete"
      >未登録</InputSquareImage>
    </div>

    <div class="name-area">
      <TitleLabel class="name-area__title" :required="true">会社名</TitleLabel>
      <InputText
        class="name-area__text"
        :maxlength="255"
        :errors="v$.name.$errors"
        v-model="v$.name.$model"
      ></InputText>
    </div>

    <div class="tel-area">
      <TitleLabel class="tel-area__title">TEL（ハイフン不要）</TitleLabel>
      <InputTel class="tel-area__tel" :errors="v$.tel.$errors" v-model="v$.tel.$model"></InputTel>
    </div>

    <div class="zip-area">
      <TitleLabel class="zip-area__title">郵便番号（ハイフン不要）</TitleLabel>
      <div class="zip-area__input">
        <InputText class="zip-area__text" :errors="v$.zipCode.$errors" v-model="v$.zipCode.$model"></InputText>
        <PrimaryButton class="zip-area__button" @click="getZipCode">住所検索</PrimaryButton>
      </div>
    </div>

    <div class="prefecture-area">
      <TitleLabel class="prefecture-area__title">都道府県</TitleLabel>
      <InputText
        class="prefecture-area__text"
        :errors="v$.prefecture.$errors"
        v-model="v$.prefecture.$model"
      ></InputText>
    </div>

    <div class="city-area">
      <TitleLabel class="city-area__title">市区町村</TitleLabel>
      <InputText class="city-area__text" :errors="v$.city.$errors" v-model="v$.city.$model"></InputText>
    </div>

    <div class="rest-address-area">
      <TitleLabel class="rest-address-area__title">以降の住所</TitleLabel>
      <InputText
        class="rest-address-area__title"
        :errors="v$.restAddress.$errors"
        v-model="v$.restAddress.$model"
      ></InputText>
    </div>

    <div class="main-image-area">
      <TitleLabel class="main-image-area__title">メイン画像</TitleLabel>
      <InputSquareImage
        class="main-image-area__image"
        :accept="['image/jpeg', 'image/png']"
        :preview="uploadImages[0].preview"
        @upload="onImageChange($event, 1)"
        @fileDelete="onImageDelete(1)"
      >未登録</InputSquareImage>
    </div>

    <div class="sub-image-area">
      <TitleLabel class="sub-image-area__title">サブ画像</TitleLabel>
      <div class="sub-image-area__input">
        <InputSquareImage
          class="sub-image-area__image"
          :accept="['image/jpeg', 'image/png']"
          :preview="uploadImages[1].preview"
          @upload="onImageChange($event, 2)"
          @fileDelete="onImageDelete(2)"
        >未登録</InputSquareImage>
        <InputSquareImage
          class="sub-image-area__image"
          :accept="['image/jpeg', 'image/png']"
          :preview="uploadImages[2].preview"
          @upload="onImageChange($event, 3)"
          @fileDelete="onImageDelete(3)"
        >未登録</InputSquareImage>
        <InputSquareImage
          class="sub-image-area__image"
          :accept="['image/jpeg', 'image/png']"
          :preview="uploadImages[3].preview"
          @upload="onImageChange($event, 4)"
          @fileDelete="onImageDelete(4)"
        >未登録</InputSquareImage>
      </div>
    </div>

    <div class="mail-area">
      <TitleLabel class="mail-area__title" :required="true">メールアドレス</TitleLabel>
      <InputEMail class="mail-area__text" :errors="v$.email.$errors" v-model="v$.email.$model"></InputEMail>
    </div>

    <div class="submit-area">
      <PrimaryButton class="submit-area__submit" @click="update">更新</PrimaryButton>
    </div>
  </Section>
</template>

<style lang="scss" scoped>
@use "~@/variables";
@use "~@/mixins";
#page-profile {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin: 0 auto;
  width: clamp(200px, 80%, 500px);
  @include mixins.mq(sp) {
    row-gap: 30px;
  }
  @include mixins.mq(tablet) {
    row-gap: 40px;
  }
  @include mixins.mq(pc) {
    row-gap: 40px;
  }
}
#page-profile {
  .title {
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
  // .section-item {
  //   width: 100%;
  // }
  .logo-area {
    width: clamp(200px, 50%, 300px);
    // &__title {
    // }
    // &__image {
    // }
  }
  .name-area {
    width: 100%;
    // &__title {
    // }
    // &__text {
    // }
  }
  .tel-area {
    width: 100%;
    // &__title {
    // }
    // &__tel {
    // }
  }
  .zip-area {
    width: 100%;
    // &__title {
    // }
    &__input {
      display: flex;
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
  .prefecture-area {
    width: 100%;
    // &__title {
    // }
    // &__text {
    // }
  }
  .city-area {
    width: 100%;
    // &__title {
    // }
    // &__text {
    // }
  }
  .rest-address-area {
    width: 100%;
    // &__title {
    // }
    // &__text {
    // }
  }
  .main-image-area {
    width: 100%;
    // &__title {
    // }
    // &__image {
    // }
  }
  .sub-image-area {
    width: 100%;
    // &__title {
    // }
    &__input {
      display: flex;
      justify-content: space-between;
      width: 100%;
    }
    &__image {
      width: 30%;
    }
  }
  .mail-area {
    width: 100%;
    // &__title {
    // }
    // &__text {
    // }
  }
  .submit-area {
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
    // &__submit {
    // }
  }
}
</style>
