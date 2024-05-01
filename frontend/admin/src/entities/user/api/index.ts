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
			refreshToken
		})
	).data;
};

export const requestResetPassword = async (email: string, registrationSource: string) => {
	return (
		await http.post('/auth/v1/user/request-reset-password', {
			email,
			registrationSource
		})
	).data;
};

export const confirmResetPassword = async (token: string, password: string)=> {
	return (
		await http.post('/auth/v1/user/confirm-reset-password', {
			token,
			password,
		})
	).data;
};
