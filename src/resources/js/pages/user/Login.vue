<script setup lang="ts">
import { reactive } from 'vue';
import { useStore } from '@/store/user/auth';
import { useRouter } from 'vue-router';
import Section from '@/components/Section.vue';

interface Form {
  email: string | null;
  password: string | null;
}

const form = reactive<Form>({
  email: null,
  password: null,
});

const router = useRouter();
const store = useStore();

const submit = () => {
  store.dispatch('login', form);
  router.push('/');
}
</script>

<template>
  <Section class="section">
    <h2 class="title">ログイン</h2>
    <input class="input" type="email" placeholder="メールアドレス" v-model="form.email" />
    <input class="input" type="password" placeholder="パスワード" v-model="form.password" />
    <button class="submit" @click="submit">ログイン</button>
  </Section>
</template>

<style lang="scss" scoped>
@use "~@/mixins";

.section {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin: 0 auto;
  row-gap: 30px;
  width: clamp(200px, 80%, 500px);
  @include mixins.mq(pc) {
    row-gap: 40px;
  }
}

.title {
  color: #dcc090;
  @include mixins.mq(sp) {
    font-size: 20px;
  }
  // @include mixins.mq(tablet) {
  // }
  @include mixins.mq(pc) {
    font-size: 36px;
  }
}

.input {
  background-color: #eee;
  border-radius: 3px;
  line-height: 50px;
  padding-left: 20px;
  padding-right: 20px;
  width: 100%;
}

.submit {
  background-color: #dcc090;
  border: 2px solid #1c1c1c;
  border-radius: 3px;
  color: #1c1c1c;
  line-height: 50px;
  letter-spacing: 0.1em;
  padding-left: 20px;
  padding-right: 20px;
  text-align: center;
  transition: all 0.4s ease;
  &:hover {
    background-color: #1c1c1c;
    border: 2px solid #dcc090;
    color: #dcc090;
    transform: translateY(-2px);
  }
  @include mixins.mq(sp) {
    width: 100%;
  }
  @include mixins.mq(tablet) {
    width: 250px;
  }
  @include mixins.mq(pc) {
    width: 250px;
  }
}
</style>
