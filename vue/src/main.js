import { createApp } from 'vue';
import store from './store/store';
import router from './router/router';
import './index.css';
import App from './App.vue';

createApp(App)
  .use(store)
  .use(router)
  .mount('#app');
