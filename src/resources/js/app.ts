import './bootstrap'
import { createApp } from 'vue';
import App from '@/App.vue';
import { router } from './router';
import { store } from './store/index';

import { library } from '@fortawesome/fontawesome-svg-core'
import { far } from '@fortawesome/free-regular-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

library.add(far)

const app = createApp(App);
store.forEach(({ modelName, key }) => {
  app.use(modelName, key)
})
app.use(router)
app.component('font-awesome-icon', FontAwesomeIcon)
app.mount("#app")
