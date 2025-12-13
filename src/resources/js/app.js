import './bootstrap';
import { createApp } from 'vue';
import router from './router';
import App from './views/App.vue'; // Root component to hold router-view

const app = createApp(App);
app.use(router);
app.mount('#app');
