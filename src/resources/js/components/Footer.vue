<template>
  <footer class="footer">
    <ul class="footer__list">
      <li class="footer__item">
        <a class="footer__link">サイトポリシー</a>
      </li>
      <li class="footer__item">
        <a class="footer__link">関連団体</a>
      </li>
      <li class="footer__item">
        <a class="footer__link">情報セキュリティ基本方針</a>
      </li>
      <li class="footer__item">
        <a class="footer__link">個人情報保護方針</a>
      </li>
      <li class="footer__item">
        <a class="footer__link">サイトマップ</a>
      </li>
    </ul>
    <p>Copyright © MASTER PIECE. All Rights Reserved</p>
  </footer>
</template>

<style lang="scss" scoped>
@use "~@/variables";
@use "~@/mixins";
@use "~@/functions";

.footer {
  background-color: variables.$base-color;
  border-top: 0.5px solid variables.$border-color;
  color: #ccc;
  display: flex;
  flex-direction: column;
  padding-top: 10px;
  padding-bottom: 10px;
  position: sticky;
  top: 100vh;
  width: 100%;
  &__item {
    line-height: 1.8;
  }
  @include mixins.mq(sp) {
    // height: functions.getFooterHeight(sp);
    padding-left: 20px;
  }
  @include mixins.mq(tablet) {
    // height: functions.getFooterHeight(tablet);
  }
  @include mixins.mq(pc) {
    align-items: center;
    font-size: 20px;
    // height: functions.getFooterHeight(pc);
    row-gap: 100px;
    &__list {
      display: flex;
      flex-wrap: wrap;
    }
    &__item {
      width: 50%;
    }
  }
}
</style>
