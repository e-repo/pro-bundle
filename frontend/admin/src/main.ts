import { createApp } from 'vue';
import App from './app/app.vue';
import router from './router/router';
import { createPinia } from 'pinia';

import './style.scss';

createApp(App)
	.use(createPinia)
	.use(router)
	.mount('#app');
