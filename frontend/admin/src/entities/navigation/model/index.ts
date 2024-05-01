import { defineStore } from 'pinia';
import { NavigationApi } from '../index';

interface Drawer {
	drawer: boolean;
	rail: boolean;
}

export const useNavigationModel = defineStore({
	id: 'navigation',

	state: () => ({
		drawer: true,
		rail: false
	} as Drawer),

	actions: {
		close(): void {
			this.rail = true;
		},
		open(): void {
			this.rail = false;
		},
		async getServiceMenuItems() {
			return await NavigationApi.fetchServiceMenuItems();
		},
		async getBlogMenuItems() {
			return await NavigationApi.fetchBlogMenuItems();
		}
	},

	persist: true
});
