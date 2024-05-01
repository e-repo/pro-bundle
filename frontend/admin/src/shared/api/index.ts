import axios, { AxiosError } from 'axios';
import { Emitter } from '@/shared/lib';

export const REFRESH_TOKEN_EVENT_NAME = 'refresh-token';


interface Token {
	isAuthenticated: boolean,
	token: string
}

export const http = axios.create({
	baseURL: import.meta.env.VITE_BASE_URL,
	headers: {
		'Content-type': 'application/json'
	}
});

export const useHttpBearerToken = () => {
	const userToken: Token = JSON.parse(localStorage.getItem('user') || '')?.user;

	return axios.create({
		baseURL: import.meta.env.VITE_BASE_URL,
		headers: {
			'Content-type': 'application/json',
			'Authorization': `Bearer ${userToken.token}`
		}
	});
};

export const tryRefreshToken = (error: unknown): boolean => {
	if (! (error instanceof AxiosError)) {
		return false;
	}

	const responseData = error.response?.data;

	if (
		responseData.code === 401 &&
		responseData.message === 'Expired JWT Token'
	) {
		Emitter.emit(REFRESH_TOKEN_EVENT_NAME);
	}

	return true;
};

