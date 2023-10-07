import { defineComponent, ref } from 'vue';

export default defineComponent({
	setup() {
		const pageName = ref<string>('Страница входа в админку');

		return {
			pageName,
		};
	},
});