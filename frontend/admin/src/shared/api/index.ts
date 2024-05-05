import axios, { AxiosError } from 'axios';
import { Emitter } from '@/shared/lib';

export const REFRESH_TOKEN_EVENT_NAME = 'refresh-token';

export const http = axios.create({
	baseURL: import.meta.env.VITE_BASE_URL,
	headers: {
		'Content-type': 'application/json'
	}
});

export const useHttpBearerToken = () => {
	const localStorageToken = localStorage.getItem('user');
	let token: string | null = null;

	if (null !== localStorageToken) {
		token = JSON.parse(localStorageToken)?.user.token;
	}

	if (null === token) {
		Emitter.emit(REFRESH_TOKEN_EVENT_NAME);
	}

	return axios.create({
		baseURL: import.meta.env.VITE_BASE_URL,
		headers: {
			'Content-type': 'application/json',
			'Authorization': `Bearer ${token}`
		}
	});
};

export const tryRefreshToken = (error: unknown): boolean => {
	if (! (error instanceof AxiosError)) {
		return false;
	}

	const responseData = error.response?.data;

	if (responseData.code === 401) {
		Emitter.emit(REFRESH_TOKEN_EVENT_NAME);
	}

	return true;
};

