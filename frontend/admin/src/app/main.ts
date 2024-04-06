import { createApp } from 'vue';
import App from './App.vue';
import router from './router/router.ts';
import { createPinia } from 'pinia';

// Vuetify
import 'vuetify/styles';
import { createVuetify } from 'vuetify';
import * as components from 'vuetify/components';
import * as directives from 'vuetify/directives';

import '@mdi/font/css/materialdesignicons.css';
import { aliases, mdi } from 'vuetify/iconsets/mdi';

const vuetify = createVuetify({
	icons: {
		defaultSet: 'mdi',
		aliases,
		sets: {
			mdi,
		},
	},
	components,
	directives,
});

createApp(App)
	.use(createPinia())
	.use(router)
	.use(vuetify)
	.mount('#app');
