import './bootstrap'
import { createApp } from 'vue';
import App from './App.vue';
import { router } from './router'
import { store } from './store/index'

const app = createApp(App);
store.forEach(({ modelName, key }) => {
  app.use(modelName, key)
})
app.use(router);
app.mount("#app");
