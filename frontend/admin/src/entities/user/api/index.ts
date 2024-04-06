import { http } from '@/shared/api';

export const fetchToken = async (username: string, password: string) => {
	return (
		await http.post('/auth/login-check', {
			username,
			password
		})
	).data;
};

export const refreshToken = async (refreshToken: string) => {
	return (
		await http.post('/auth/token-refresh', {
			refresh_token: refreshToken
		})
	).data;
};
