import { defineComponent, ref } from 'vue';

export default defineComponent({
	setup() {
		const pageName = ref<string>('Главная страница');

		return {
			pageName,
		};
	},
});