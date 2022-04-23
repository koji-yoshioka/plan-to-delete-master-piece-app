<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { useVuelidate, ValidationArgs } from '@vuelidate/core'
import { required, email, maxLength, helpers } from '@vuelidate/validators'
import axios from 'axios'
import Section from '@/components/Section.vue'

import TitleLabel from '@/components/TitleLabel.vue'

import InputSquareImage from '@/components/InputSquareImage.vue'
import InputText from '@/components/InputText.vue'
import InputTextArea from '@/components/InputTextArea.vue'
import InputTel from '@/components/InputTel.vue'
import InputEMail from '@/components/InputEMail.vue'
import PrimaryButton from '@/components/PrimaryButton.vue'

import { ReqLogo, ReqImage, ResCompanyProfile, ResZipAddress, Holiday, PaymentMethod, SellingPoint } from '@/typings/interface/company/profile'

const plainFields = ref<ValidationArgs>({
  id: null,
  name: null,
  email: null,
  tel: null,
  zipCode: null,
  prefecture: null,
  city: null,
  restAddress: null,
  nearestStation: null,
  businessHoursFrom: null,
  businessHoursTo: null,
  comment: null,
  note: null,
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
  nearestStation: {},
  businessHoursFrom: {},
  businessHoursTo: {},
  comment: {},
  note: {},
}
const v$ = useVuelidate(rules, plainFields)

// DBに存在する全てのセールスポイント
const allSellingPoints = ref<SellingPoint[]>([])
// DBに存在する全ての支払い方法
const allPaymentMethods = ref<PaymentMethod[]>([])
// DBに存在する全ての休業日
const allHolidays = ref<Holiday[]>([])

// 選択したセールスポイント
const reqSellingPointIds = ref<number[]>([])
// 選択した休業日
const reqPaymentMethodIds = ref<number[]>([])
// 選択した支払い方法
const reqHolidayIds = ref<number[]>([])

const reqLogo = ref<ReqLogo>({
  logo: null,
  preview: null,
  isChanged: false,
})

const reqImages = ref<ReqImage[]>([
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
    const res: ResCompanyProfile = await axios.get('/api/company/1')
    console.log('res', res);

    v$.value.id.$model = 1
    v$.value.name.$model = res.data.name
    v$.value.email.$model = res.data.email
    v$.value.tel.$model = res.data.tel
    v$.value.zipCode.$model = res.data.zipCode
    v$.value.prefecture.$model = res.data.prefecture
    v$.value.city.$model = res.data.city
    v$.value.restAddress.$model = res.data.restAddress
    v$.value.nearestStation.$model = res.data.nearestStation
    v$.value.comment.$model = res.data.comment
    v$.value.note.$model = res.data.note
    reqLogo.value.preview = res.data.logo
      ? 'https://s3-ap-northeast-1.amazonaws.com/master-piece-company-images/1/logo/' + res.data.logo
      : null
    reqImages.value.map(reqImage => {
      res.data.images.forEach(image => {
        if (image.displayNo === reqImage.displayNo) {
          reqImage.preview = 'https://s3-ap-northeast-1.amazonaws.com/master-piece-company-images/1/images/' + image.fileName
        }
        return image
      })
    })
    res.data.sellingPoints.forEach(sellingPoint => {
      allSellingPoints.value.push(sellingPoint)
      if (sellingPoint.selected) {
        reqSellingPointIds.value.push(sellingPoint.id)
      }
    })
    res.data.paymentMethods.forEach(paymentMethod => {
      allPaymentMethods.value.push(paymentMethod)
      if (paymentMethod.selected) {
        reqPaymentMethodIds.value.push(paymentMethod.id)
      }
    })
    res.data.holidays.forEach(holiday => {
      allHolidays.value.push(holiday)
      if (holiday.selected) {
        reqHolidayIds.value.push(holiday.id)
      }
    })
  }
)

const onLogoChange = (logo: File) => {
  const reader = new FileReader()
  reader.onload = onloadEvent => {
    reqLogo.value.preview = onloadEvent.target ? onloadEvent.target.result : null
  }
  reader.readAsDataURL(logo)
  reqLogo.value.logo = logo
  reqLogo.value.isChanged = true
}

const onImageChange = (image: File, displayNo: number) => {
  const reader = new FileReader()
  reader.onload = onloadEvent => {
    reqImages.value.map(reqImage => {
      if (reqImage.displayNo === displayNo) {
        reqImage.preview = onloadEvent.target ? onloadEvent.target.result : null
      }
      return reqImage
    })
  }
  reader.readAsDataURL(image)
  reqImages.value.map(reqImage => {
    if (reqImage.displayNo === displayNo) {
      reqImage.image = image
      reqImage.isChanged = true
    }
    return reqImage
  })
}

const onLogoDelete = () => {
  reqLogo.value.logo = null
  reqLogo.value.preview = null
  reqLogo.value.isChanged = true
}

const onImageDelete = (displayNo: number) => {
  reqImages.value.map(reqImage => {
    if (reqImage.displayNo === displayNo) {
      reqImage.image = null
      reqImage.preview = null
      reqImage.isChanged = true
    }
    return reqImage
  })
}

const jsonpAdapter = require('axios-jsonp')
const getZipCode = async () => {
  const res: ResZipAddress = await axios.get(`https://api.zipaddress.net/?zipcode=${v$.value.zipCode.$model}`, { adapter: jsonpAdapter })
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
  if (v$.value.nearestStation.$model && typeof v$.value.nearestStation.$model === 'string') {
    formData.append('nearestStation', v$.value.nearestStation.$model)
  }
  if (v$.value.businessHoursFrom.$model && typeof v$.value.businessHoursFrom.$model === 'string') {
    formData.append('businessHoursFrom', v$.value.businessHoursFrom.$model)
  }
  if (v$.value.businessHoursTo.$model && typeof v$.value.businessHoursTo.$model === 'string') {
    formData.append('businessHoursTo', v$.value.businessHoursTo.$model)
  }
  if (v$.value.comment.$model && typeof v$.value.comment.$model === 'string') {
    formData.append('comment', v$.value.comment.$model)
  }
  if (v$.value.note.$model && typeof v$.value.note.$model === 'string') {
    formData.append('note', v$.value.note.$model)
  }
  if (reqLogo.value.isChanged) {
    formData.append('logo', reqLogo.value.logo ? reqLogo.value.logo : '')
  }
  reqImages.value
    .filter(reqImage => reqImage.isChanged)
    .forEach(reqImage => {
      formData.append('images[' + reqImage.displayNo + ']', reqImage.image ? reqImage.image : '')
    })

  reqSellingPointIds.value.forEach(reqSellingPointId => {
    formData.append('sellingPointIds[' + reqSellingPointId + ']', reqSellingPointId.toString())
  })
  reqPaymentMethodIds.value.forEach(reqPaymentMethodId => {
    formData.append('paymentMethodIds[' + reqPaymentMethodId + ']', reqPaymentMethodId.toString())
  })
  reqHolidayIds.value.forEach(reqHolidayId => {
    formData.append('holidayIds[' + reqHolidayId + ']', reqHolidayId.toString())
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
  <div class="page-profile">
    <Section class="page-profile__section">
      <h2 class="page-profile__title">会社情報編集</h2>

      <div class="page-profile__section-item is-logo">
        <TitleLabel class="page-profile__section-item-title">ロゴ画像</TitleLabel>
        <InputSquareImage class="page-profile__section-item-input"
          :accept="['image/jpeg', 'image/png', 'image/svg+xml']" :preview="reqLogo.preview" @upload="onLogoChange"
          @fileDelete="onLogoDelete"></InputSquareImage>
      </div>

      <div class="page-profile__section-item">
        <TitleLabel class="page-profile__section-item-title" :required="true">会社名</TitleLabel>
        <InputText class="page-profile__section-item-input" :maxlength="255" :errors="v$.name.$errors"
          v-model="v$.name.$model">
        </InputText>
      </div>

      <div class="page-profile__section-item">
        <TitleLabel class="page-profile__section-item-title" :required="true">メールアドレス</TitleLabel>
        <InputEMail class="page-profile__section-item-input" :errors="v$.email.$errors" v-model="v$.email.$model">
        </InputEMail>
      </div>

      <div class="page-profile__section-item">
        <TitleLabel class="page-profile__section-item-title">TEL（ハイフン不要）</TitleLabel>
        <InputTel class="page-profile__section-item-input" :errors="v$.tel.$errors" v-model="v$.tel.$model"></InputTel>
      </div>

      <div class="page-profile__section-item">
        <TitleLabel class="page-profile__section-item-title">郵便番号（ハイフン不要）</TitleLabel>
        <div class="page-profile__section-item-zip-info">
          <InputText class="page-profile__section-item-zip-info--input" :errors="v$.zipCode.$errors"
            v-model="v$.zipCode.$model">
          </InputText>
          <PrimaryButton class="page-profile__section-item-zip-info--button" @click="getZipCode">住所検索</PrimaryButton>
        </div>
      </div>

      <div class="page-profile__section-item">
        <TitleLabel class="page-profile__section-item-title">都道府県</TitleLabel>
        <InputText class="page-profile__section-item-input" :errors="v$.prefecture.$errors"
          v-model="v$.prefecture.$model">
        </InputText>
      </div>

      <div class="page-profile__section-item">
        <TitleLabel class="page-profile__section-item-title">市区町村</TitleLabel>
        <InputText class="page-profile__section-item-input" :errors="v$.city.$errors" v-model="v$.city.$model">
        </InputText>
      </div>

      <div class="page-profile__section-item">
        <TitleLabel class="page-profile__section-item-title">以降の住所</TitleLabel>
        <InputText class="page-profile__section-item-input" :errors="v$.restAddress.$errors"
          v-model="v$.restAddress.$model">
        </InputText>
      </div>

      <div class="page-profile__section-item">
        <TitleLabel class="page-profile__section-item-title">最寄り駅</TitleLabel>
        <InputText class="page-profile__section-item-input" :errors="v$.nearestStation.$errors"
          v-model="v$.nearestStation.$model">
        </InputText>
      </div>

      <div class="page-profile__section-item">
        <TitleLabel class="page-profile__section-item-title">メイン画像</TitleLabel>
        <InputSquareImage class="page-profile__section-item-input" :accept="['image/jpeg', 'image/png']"
          :preview="reqImages[0].preview" @upload="onImageChange($event, 1)" @fileDelete="onImageDelete(1)">未登録
        </InputSquareImage>
      </div>

      <div class="page-profile__section-item">
        <TitleLabel class="page-profile__section-item-title">サブ画像</TitleLabel>
        <div class="page-profile__section-item-sub-images">
          <InputSquareImage class="page-profile__section-item-sub-images--input" :accept="['image/jpeg', 'image/png']"
            :preview="reqImages[1].preview" @upload="onImageChange($event, 2)" @fileDelete="onImageDelete(2)">
          </InputSquareImage>
          <InputSquareImage class="page-profile__section-item-sub-images--input" :accept="['image/jpeg', 'image/png']"
            :preview="reqImages[2].preview" @upload="onImageChange($event, 3)" @fileDelete="onImageDelete(3)">
          </InputSquareImage>
          <InputSquareImage class="page-profile__section-item-sub-images--input" :accept="['image/jpeg', 'image/png']"
            :preview="reqImages[3].preview" @upload="onImageChange($event, 4)" @fileDelete="onImageDelete(4)">
          </InputSquareImage>
        </div>
      </div>

      <div class="page-profile__section-item">
        <TitleLabel class="page-profile__section-item-title">セールスポイント</TitleLabel>
        <div class="page-profile__section-item-checkbox-list">
          <div class="page-profile__section-item-checkbox" v-for="sellingPoint in allSellingPoints"
            :key="sellingPoint.id">
            <input class="page-profile__section-item-checkbox--input" :id="`selling-point-${sellingPoint.id}`"
              :value="sellingPoint.id" type="checkbox" v-model="reqSellingPointIds" />
            <label class="page-profile__section-item-checkbox--label" :for="`selling-point-${sellingPoint.id}`">{{
              sellingPoint.name
            }}</label>
          </div>
        </div>
      </div>

      <div class="page-profile__section-item">
        <TitleLabel class="page-profile__section-item-title">支払い方法</TitleLabel>
        <div class="page-profile__section-item-checkbox-list">
          <div class="page-profile__section-item-checkbox" v-for="paymentMethod in allPaymentMethods"
            :key="paymentMethod.id">
            <input class="page-profile__section-item-checkbox--input" :id="`payment-method-${paymentMethod.id}`"
              :value="paymentMethod.id" type="checkbox" v-model="reqPaymentMethodIds" />
            <label class="page-profile__section-item-checkbox--label" :for="`payment-method-${paymentMethod.id}`">{{
              paymentMethod.name
            }}</label>
          </div>
        </div>
      </div>

      <div class="page-profile__section-item">
        <TitleLabel class="page-profile__section-item-title">休業日</TitleLabel>
        <div class="page-profile__section-item-checkbox-list">
          <div class="page-profile__section-item-checkbox" v-for="holiday in allHolidays" :key="holiday.id">
            <input class="page-profile__section-item-checkbox--input" :id="`holiday-${holiday.id}`" :value="holiday.id"
              type="checkbox" v-model="reqHolidayIds" />
            <label class="page-profile__section-item-checkbox--label" :for="`holiday-${holiday.id}`">{{
              holiday.name
            }}</label>
          </div>
        </div>
      </div>

      <div class="page-profile__section-item">
        <TitleLabel class="page-profile__section-item-title">コメント</TitleLabel>
        <InputTextArea class="page-profile__section-item-input" :errors="v$.comment.$errors"
          v-model="v$.comment.$model">
        </InputTextArea>
      </div>

      <div class="page-profile__section-item">
        <TitleLabel class="page-profile__section-item-title">備考</TitleLabel>
        <InputTextArea class="page-profile__section-item-input" :errors="v$.note.$errors" v-model="v$.note.$model">
        </InputTextArea>
      </div>

      <div class="page-profile__section-item is-submit">
        <PrimaryButton class="page-profile__section-item-submit" @click="update">更新</PrimaryButton>
      </div>
    </Section>
  </div>
</template>

<style lang="scss" scoped>
@use "~@/variables";
@use "~@/mixins";

.page-profile {}

.page-profile__section {
  align-items: center;
  display: flex;
  flex-direction: column;
  margin: 0 auto;
  width: clamp(200px, 80%, 500px);

  @include mixins.mq(sp) {
    row-gap: 30px;
  }

  @include mixins.mq(tablet, pc) {
    row-gap: 40px;
  }
}

.page-profile__title {
  color: #dcc090;
  font-size: 1.5em;
}

.page-profile__section-item {
  width: 100%;
}

.page-profile__section-item.is-logo {
  width: clamp(200px, 50%, 300px);
}

.page-profile__section-item.is-submit {
  @include mixins.mq(sp) {
    width: 100%;
  }

  @include mixins.mq(tablet, pc) {
    max-width: 250px;
    width: 100%;
  }
}

.page-profile__section-item-title {}

.page-profile__section-item-input {}

.page-profile__section-item-submit {
  width: 100%;
}

.page-profile__section-item-zip-info {
  display: flex;
  justify-content: space-between;
}

.page-profile__section-item-zip-info--input {
  width: 45%;
}

.page-profile__section-item-zip-info--button {
  width: 45%;
}

.page-profile__section-item-sub-images {
  display: flex;
  justify-content: space-between;
}

.page-profile__section-item-sub-images--input {
  width: 30%;
}

.page-profile__section-item-checkbox-list {
  display: flex;
  flex-direction: column;
  gap: 20px
}

.page-profile__section-item-checkbox {
  align-items: center;
  display: flex;
  gap: 10px
}

.page-profile__section-item-checkbox--input {
  transform: scale(1.3);
}

.page-profile__section-item-checkbox--label {
  color: #ccc;
}
</style>
