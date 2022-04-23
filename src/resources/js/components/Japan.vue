<script setup lang="ts">
import { onMounted, ref } from 'vue'
import PrimaryButton from '@/components/PrimaryButton.vue'
import ModalBackground from '@/components/ModalBackground.vue'
import Modal from '@/components/Modal.vue'
import axios from 'axios'

const showModal = ref<boolean>(false)

interface Region {
  regionId: number
  regionName: string
  prefectures: {
    prefectureId: number
    prefectureName: string
  }[]
}

const currentRegion = ref<Region | null>(null)

const hokkaidoTohoku = ref<Region>()
const kanto = ref<Region | null>(null)
const chubu = ref<Region | null>(null)
const kinki = ref<Region | null>(null)
const chugoku = ref<Region | null>(null)
const shikoku = ref<Region | null>(null)
const kyushuOkinawa = ref<Region | null>(null)



const onSelectRegion = (region: Region) => {
  currentRegion.value = region
  showModal.value = true
}



onMounted(
  async () => {
    const res: {
      data: {
        regionId: number
        regionName: string
        prefectures: {
          prefectureId: number
          prefectureName: string
        }[]
      }[]
    } = await axios.get('/api/area/regions')

    res.data.forEach(region => {
      switch (region.regionName) {
        case '北海道・東北':
          hokkaidoTohoku.value = region
          break
        case '関東':
          kanto.value = region
          break
        case '中部':
          chubu.value = region
          break
        case '近畿':
          kinki.value = region
          break
        case '中国':
          chugoku.value = region
          break
        case '四国':
          shikoku.value = region
          break
        case '九州・沖縄':
          kyushuOkinawa.value = region
          break
        default:
          break
      }
    })
  })

const close = () => {
  console.log('call close')
  currentRegion.value = null
  showModal.value = false
}

</script>

<template>
  <div class="c-japan">
    <figure class="c-japan__map-area">
      <img class="c-japan__map-image" src="/images/japan.png" alt="japan" />
      <div class="c-japan__button-group">

        <PrimaryButton class="c-japan__button is-hokkaido-tohoku" @click="onSelectRegion(hokkaidoTohoku)">北海道・東北
        </PrimaryButton>
        <PrimaryButton class="c-japan__button is-kanto" @click="onSelectRegion(kanto)">関東</PrimaryButton>
        <PrimaryButton class="c-japan__button is-chubu" @click="onSelectRegion(chubu)">中部</PrimaryButton>
        <PrimaryButton class="c-japan__button is-kinki" @click="onSelectRegion(kinki)">近畿</PrimaryButton>
        <PrimaryButton class="c-japan__button is-chugoku" @click="onSelectRegion(chugoku)">中国</PrimaryButton>
        <PrimaryButton class="c-japan__button is-shikoku" @click="onSelectRegion(shikoku)">四国</PrimaryButton>
        <PrimaryButton class="c-japan__button is-kyushu-okinawa" @click="onSelectRegion(kyushuOkinawa)">九州・沖縄
        </PrimaryButton>

        <ModalBackground :show="showModal" @close="close">
          <Modal :title="'都道府県を選択'" :show="showModal" @close="close">
            <div class="c-japan__prefecture-group">
              <router-link class="c-japan__prefecture" v-for="prefecture in currentRegion.prefectures"
                :key="prefecture.prefectureId"
                :to="{ name: 'search', query: { prefectureId: prefecture.prefectureId } }" exact>
                {{ prefecture.prefectureName }}
              </router-link>
            </div>
          </Modal>
        </ModalBackground>

      </div>
    </figure>
  </div>
</template>

<style lang="scss" scoped>
@use "~@/variables";
@use "~@/mixins";

.c-japan__prefecture-group {
  display: grid;
  gap: 20px;
  grid-template-columns: 1fr 1fr;
}

.c-japan__prefecture {
  border: 2px solid #1c1c1c;
  border-radius: 10px;
  display: block;
  padding: 10px;
  text-align: center;
}

.c-japan {
  width: 100%;

  &__map-area {
    position: relative;
    width: 100%;

    @include mixins.mq(sp) {}

    @include mixins.mq(tablet, pc) {
      &::before {
        content: "";
        display: block;
        padding-top: 100%;
      }
    }
  }

  &__map-image {
    @include mixins.mq(sp) {
      display: none;
    }

    @include mixins.mq(tablet, pc) {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
    }
  }

  &__button-group {
    @include mixins.mq(sp) {
      align-items: center;
      display: flex;
      flex-direction: column;
      row-gap: 5px;
    }

    @include mixins.mq(tablet, pc) {}
  }

  &__button {
    background-color: #eee;
    border-radius: 10px;
    border: 1px solid #dcc090;
    color: #666;
    // font-size: 14px;
    height: initial;
    padding: 5px 10px;
    text-align: center;

    // width: 100px;
    @include mixins.mq(sp) {
      font-size: 12px;
      max-width: 250px;
      width: 100%;
    }

    @include mixins.mq(tablet, pc) {
      &.is-hokkaido-tohoku {
        position: absolute;
        top: 25%;
        right: 29%;
      }

      &.is-kanto {
        position: absolute;
        top: 52%;
        right: 39%;
      }

      &.is-chubu {
        position: absolute;
        top: 52%;
        right: 49%;
      }

      &.is-kinki {
        position: absolute;
        top: 57%;
        right: 57%;
      }

      &.is-chugoku {
        position: absolute;
        top: 54%;
        right: 70%;
      }

      &.is-shikoku {
        position: absolute;
        top: 61%;
        right: 69%;
      }

      &.is-kyushu-okinawa {
        position: absolute;
        bottom: 32%;
        left: 10%;
      }
    }
  }
}
</style>
