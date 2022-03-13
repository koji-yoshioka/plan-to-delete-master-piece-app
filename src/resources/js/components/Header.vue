<script setup lang="ts">
// import { defineComponent } from "vue";
// export default defineComponent({
//   setup() { },
// });
</script>

<template>
  <header class="header">
    <router-link to="/" exact>
      <h1>
        <figure class="logo">
          <img class="logo__img" src="/images/logo.svg" alt="MASTER PIECE" />
        </figure>
      </h1>
    </router-link>
    <nav class="header-menu">
      <ul class="header-menu__item-list">
        <li class="header-menu__item">
          <router-link to="/login" exact>ログイン</router-link>
        </li>
        <li class="header-menu__item">
          <router-link to="/sign-up" exact>新規登録</router-link>
        </li>
      </ul>
    </nav>
  </header>
</template>

<style lang="scss" scoped>
@use "~@/variables";
@use "~@/mixins";
@use "~@/functions";
.header {
  align-items: center;
  background-color: variables.$base-color;
  border-bottom: 0.5px solid variables.$border-color;
  color: variables.$font-color;
  display: flex;
  justify-content: space-between;
  position: fixed;
  width: 100%;
  z-index: 2;
  @include mixins.mq(sp) {
    height: functions.getHeaderHeight(sp);
    padding: 5px 10px;
  }
  @include mixins.mq(tablet) {
    height: functions.getHeaderHeight(tablet);
    padding: 10px 15px;
  }
  @include mixins.mq(pc) {
    font-size: 20px;
    height: functions.getHeaderHeight(pc);
    padding: 10px 20px;
  }
}

.logo {
  @include mixins.mq(sp) {
    width: 50px;
    &__img {
      width: 100%;
    }
  }
  @include mixins.mq(tablet) {
    width: 60px;
    &__img {
      width: 100%;
    }
  }
  @include mixins.mq(pc) {
    width: 80px;
    &__img {
      width: 100%;
    }
  }
}

.header-menu__item-list {
  display: flex;
  flex: 1;
  justify-content: flex-end;
}
.header-menu__item {
  margin-right: 20px;
}
.header-menu__item:last-child {
  margin-right: 0;
}
</style>
