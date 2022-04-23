<script setup lang="ts">
import { computed, onMounted, ref } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'

import Section from '@/components/Section.vue'
import InputNumber from '@/components/InputNumber.vue'
import PrimaryButton from '@/components/PrimaryButton.vue'
import VerticalTable from '@/components/VerticalTable.vue'
import VerticalTableRow from '@/components/VerticalTableRow.vue'
import SearchResultCompany from '@/components/SearchResultCompany.vue'
import ModalBackground from '@/components/ModalBackground.vue'
import Modal from '@/components/Modal.vue'

import { Company, ResCities, ResCompanies } from '@/typings/interface/company/search'

const cities = ref<ResCities>({ data: [] })

const getTotalPageCount = computed(() => Math.ceil(companies.value.length) / perPage.value)
const perPage = ref<number>(2)

const currentPageNumber = ref<number>(1)

const updateHandler = (pageNumber: number) => {
  currentPageNumber.value = pageNumber
}

const companies = ref<Company[]>([])
const getCompanies = computed(() => {
  let current = currentPageNumber.value * perPage.value
  let start = current - perPage.value;
  return companies.value.slice(start, current);
})


const prefectureId = ref<string>('')
// const cities = ref<City[]>([])
const showModal = ref<boolean>(false)

const selectedCities = ref<string[]>([])

const close = () => {
  showModal.value = false
}

const search = async () => {
  const res: ResCompanies = await axios.get('/api/company', {
    params: {
      prefectureId: prefectureId.value,
      cities: selectedCities.value,
    }
  })
  companies.value = res.data
  console.log('>>>searh result:', companies.value)
}
onMounted(
  async () => {
    // クエリ名が不正あるいはパラメータの値が不正であればエラーとする
    const { prefectureId: paramPrefectureId } = useRoute().query
    prefectureId.value = paramPrefectureId ? paramPrefectureId.toString() : ''
    console.log('prefectureId is ' + prefectureId.value)
    if (!prefectureId.value) {
      console.log('falsy')
    }
    if (!prefectureId.value || !/^[1-9]|[1-3][0-9]|4[0-7]$/.test(prefectureId.value)) {
      // TODO:falsyまたは1~47の数値ではない場合
      console.log('error occured. prefectureId is ' + prefectureId.value)
    }
    // 市区町村を取得
    cities.value = await axios.get(`/api/common/cities/${prefectureId.value}`)
    // cities.value = res.data
  })
</script>

<template>
  <div class="page-search">
    <Section class="page-search__condition-area">
      <VerticalTable>
        <VerticalTableRow :columnName="'エリア'">
          <PrimaryButton class="page-search__area-filter-button" @click="showModal = true">エリアを絞り込む</PrimaryButton>
        </VerticalTableRow>
        <VerticalTableRow :columnName="'料金'">
          <div class="page-search__price-range-area">
            <InputNumber class="page-search__price-range" />〜
            <InputNumber class="page-search__price-range" />
          </div>
        </VerticalTableRow>
        <VerticalTableRow :columnName="'条件'">
          <PrimaryButton class="page-search__add-condition-button">条件を追加する</PrimaryButton>
        </VerticalTableRow>
      </VerticalTable>
      <div class="page-search__search-button-area">
        <PrimaryButton class="page-search__search-button" @click="search">検索する</PrimaryButton>
      </div>
    </Section>

    <ModalBackground :show="showModal" @close="close">
      <Modal class="page-search__modal" :title="'市区町村を選択'" :show="showModal" @close="close">
        <div v-for="city in cities.data" :key="city.id">
          <input :id="`${city.id}`" :value="city.name" type="checkbox" v-model="selectedCities" />
          <label :for="`${city.id}`">{{ city.name }}</label>
        </div>
      </Modal>
    </ModalBackground>

    <Section class="page-search__result-area">
      <SearchResultCompany v-for="company in getCompanies" :company="company"></SearchResultCompany>
      <v-pagination v-model="currentPageNumber" :pages="getTotalPageCount" :range-size="3" active-color="#dcc090"
        @update:modelValue="updateHandler" />
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

  @include mixins.mq(sp) {
    padding-left: 10px;
    padding-right: 10px;
  }

  @include mixins.mq(tablet) {
    padding-left: 50px;
    padding-right: 50px;
  }

  @include mixins.mq(pc) {
    padding-left: 70px;
    padding-right: 70px;
  }
}

.page-search__condition-area {
  max-width: 860px;
  width: 100%;
}

.page-search__area-filter-button {
  @include mixins.mq(sp) {
    width: 100%;
  }

  @include mixins.mq(tablet, pc) {
    width: 200px;
  }
}

.page-search__price-range-area {
  display: flex;
  gap: 10px;

  @include mixins.mq(sp) {
    flex-direction: column;
  }

  @include mixins.mq(tablet, pc) {
    align-items: center;
  }
}

.page-search__price-range {
  background: #eee;
  border: none;
}

.page-search__add-condition-button {
  @include mixins.mq(sp) {
    width: 100%;
  }

  @include mixins.mq(tablet, pc) {
    width: 200px;
  }
}

.page-search__search-button-area {
  display: flex;
  justify-content: center;

  @include mixins.mq(sp) {
    margin-top: 40px;
  }

  @include mixins.mq(tablet) {
    margin-top: 50px;
  }

  @include mixins.mq(pc) {
    margin-top: 60px;
  }
}

.page-search__search-button {
  width: 200px;
}

.page-search__modal {
  width: 500px;
}

.page-search__result-area {
  display: flex;
  flex-direction: column;
  max-width: 860px;
  width: 100%;

  @include mixins.mq(sp) {
    row-gap: 30px;
  }

  @include mixins.mq(tablet) {
    row-gap: 45px;
  }

  @include mixins.mq(pc) {
    row-gap: 60px;
  }
}
</style>
