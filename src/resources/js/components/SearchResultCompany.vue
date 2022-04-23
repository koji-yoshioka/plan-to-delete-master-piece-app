<script setup lang="ts">
import { computed, PropType } from 'vue'
import { Company } from '@/typings/interface/company/search'

const props = defineProps({
  company: {
    type: Object as PropType<Company>,
    required: true
  },
})

const getLogo = computed(() =>
  props.company.logo
    ? `https://s3-ap-northeast-1.amazonaws.com/master-piece-company-images/${props.company.id}/logo/${props.company.logo}`
    : `https://s3-ap-northeast-1.amazonaws.com/master-piece-company-images/common/no-image.jpeg`
)

const getAddress = computed(() => {
  const prefecture = props.company.prefecture ? props.company.prefecture : ''
  const city = props.company.city ? props.company.city : ''
  const restAddress = props.company.restAddress ? props.company.restAddress : ''
  return [prefecture, city, restAddress].join(' ')
})

const getBusinessHours = computed(() => {
  const businessHoursFrom = props.company.businessHoursFrom
    ? props.company.businessHoursFrom.substring(0, 2) + ':' + props.company.businessHoursFrom.substring(2)
    : '-'
  const businessHoursTo = props.company.businessHoursTo
    ? props.company.businessHoursTo.substring(0, 2) + ':' + props.company.businessHoursTo.substring(2)
    : '-'
  return [businessHoursFrom, businessHoursTo].join(' 〜 ')
})

const getHolidays = computed(() => {
  return props.company.holidays.map(holiday => holiday.name).join(' ')
})
</script>

<template>
  <div class="c-search-result-company">
    <p class="c-search-result-company__name">{{ company.name }}</p>
    <div class="c-search-result-company__category-area">
      <div class="c-search-result-company__category" v-for="sellingPoint in company.sellingPoints">
        {{ sellingPoint.name }}
      </div>
    </div>
    <div class="c-search-result-company__content">
      <figure class="c-search-result-company__logo-area">
        <img class="c-search-result-company__logo" :src="getLogo" alt="COMPANY LOGO" />
      </figure>
      <div class="c-search-result-company__overview">
        <p class="c-search-result-company__comment">{{ company.comment }}</p>

        <div class="c-search-result-company__address-area">
          <label class="c-search-result-company__address-label">所在地</label>
          <p class="c-search-result-company__address">
            {{ getAddress }}</p>
        </div>

        <div class="c-search-result-company__access-area">
          <label class="c-search-result-company__access-label">アクセス</label>
          <p class="c-search-result-company__access">{{ company.nearestStation }}</p>
        </div>

        <div class="c-search-result-company__business-date-area">
          <label class="c-search-result-company__business-date-label">営業時間</label>
          <p class="c-search-result-company__business-date">{{ getBusinessHours }}</p>
          <label class="c-search-result-company__closed-label">休業日</label>
          <p class="c-search-result-company__closed">{{ getHolidays }}</p>
        </div>

        <div class="c-search-result-company__link-area-group">
          <div class="c-search-result-company__link-area">
            <font-awesome-icon :icon="['far', 'heart']" size="2x" />
            <!-- <font-awesome-icon :icon="['fas', 'heart']" size="2x" /> -->
            <p>お気に入り</p>
          </div>
          <div class="c-search-result-company__link-area">
            <font-awesome-icon :icon="['fas', 'calendar-days']" size="2x" />
            <p>予約する</p>
          </div>
          <div class="c-search-result-company__link-area">
            <font-awesome-icon :icon="['far', 'comment-dots']" size="2x" />
            <p>レビュー</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style lang="scss" scoped>
@use "~@/variables";
@use "~@/mixins";

.c-search-result-company {
  border: variables.$border-color 2px solid;
  width: 100%;

  @include mixins.mq(sp) {
    padding: 10px;
  }

  @include mixins.mq(tablet, pc) {
    padding: 20px;
  }
}

.c-search-result-company__name {
  color: variables.$font-color;
  font-size: 1.25em;
}

.c-search-result-company__category-area {
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
  margin: 10px 0;
}

.c-search-result-company__category {
  background-color: #fff;
  border-color: #988989;
  border-radius: 10px;
  color: #988989;
  display: inline-block;
  padding: 10px;
}

.c-search-result-company__content {
  column-gap: 20px;
  display: flex;

  @include mixins.mq(sp) {
    flex-direction: column;
    row-gap: 10px;
  }

  // @include mixins.mq(tablet) {
  // }
  // @include mixins.mq(pc) {
  // }
}

.c-search-result-company__logo-area {
  border: variables.$border-color 2px solid;
  height: 100%;
  position: relative;
  width: 200px;

  @include mixins.mq(sp) {
    width: 100%;
  }

  @include mixins.mq(tablet, pc) {}

  &::before {
    background-color: #fff;
    content: "";
    display: block;
    padding-bottom: 100%;
  }
}

.c-search-result-company__logo {
  left: 0;
  position: absolute;
  top: 0;
  width: 100%;
}

.c-search-result-company__overview {
  display: flex;
  flex-direction: column;
  flex-grow: 1;
  row-gap: 10px;
}

.c-search-result-company__comment {
  background-color: #edeae2;
  min-height: 120px;
  padding: 5px;
}

.c-search-result-company__address-area {
  display: flex;
  flex-direction: column;
  row-gap: 10px;
  width: 100%;
}

.c-search-result-company__address-label {
  color: variables.$font-color;
}

.c-search-result-company__address {
  color: #fff;
}

.c-search-result-company__access-area {
  display: flex;
  flex-direction: column;
  row-gap: 10px;
  width: 100%;
}

.c-search-result-company__access-label {
  color: variables.$font-color;
}

.c-search-result-company__access {
  color: #fff;
}

.c-search-result-company__business-date-area {
  column-gap: 10px;
  display: flex;
}

.c-search-result-company__business-date-label {
  color: variables.$font-color;
}

.c-search-result-company__business-date {
  color: #fff;
}

.c-search-result-company__closed-label {
  color: variables.$font-color;
}

.c-search-result-company__closed {
  color: #fff;
}

.c-search-result-company__link-area-group {
  align-items: center;
  background-color: #dcc090;
  display: flex;
  column-gap: 20px;
  padding: 10px;

  @include mixins.mq(sp) {
    align-items: flex-start;
    flex-direction: column;
    row-gap: 10px;
  }

  @include mixins.mq(tablet) {}

  @include mixins.mq(pc) {}
}

.c-search-result-company__link-area {
  align-items: center;
  display: flex;
  column-gap: 20px;
}
</style>
