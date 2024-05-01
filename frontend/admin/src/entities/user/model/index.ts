import { defineStore } from 'pinia';
import { UserApi } from '../index';

interface AuthStoreUser {
	isAuthenticated: boolean,
	token: string|null,
	refreshToken: string|null,
}

interface UserToken {
	token: string,
	refreshToken: string,
}

export interface AuthStore {
	user: AuthStoreUser,
}

export const useUserModel = defineStore({
	id: 'user',

	state: () => ({
		user: {
			isAuthenticated: false,
			token: null,
			refreshToken: null
		}
	} as AuthStore),

	actions: {
		async singIn(username: string, password: string) {
			const result = await UserApi.fetchToken(username, password);

			this.setToken({
				token: result.token,
				refreshToken: result.refreshToken
			});
		},
		async requestResetPassword(email: string, registrationSource: string) {
			await UserApi.requestResetPassword(email, registrationSource);
		},
		async confirmResetPassword(token: string, newPassword: string) {
			await UserApi.confirmResetPassword(token, newPassword);
		},
		logout() {
			this.user.isAuthenticated = false;
			this.user.token = null;
			this.user.refreshToken = null;
		},
		async refreshToken() {
			if (null === this.user.refreshToken) {
				throw new TypeError('Не найден токен для обновления.');
			}

			const result = await UserApi.refreshToken(this.user.refreshToken);

			this.setToken({
				token: result.token,
				refreshToken: result.refreshToken
			});
		},
		setToken(userToken: UserToken) {
			this.user.isAuthenticated = true;
			this.user.token = userToken.token;
			this.user.refreshToken = userToken.refreshToken;
		}
	},

	getters: {
		getToken(): string|null {
			return this.user.token;
		},
		getRefreshToken(): string|null {
			return this.user.refreshToken;
		},
		isAuthenticated(): boolean {
			return this.user.isAuthenticated;
		}
	},

	persist: true
});


