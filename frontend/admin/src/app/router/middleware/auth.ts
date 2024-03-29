import { MiddlewarePayload } from './type';
import { RouteLocationRaw } from 'vue-router';

export default function auth(payload: MiddlewarePayload): RouteLocationRaw | void {
	const nextMiddleware = payload.nextMiddleware;
	const isAuth: boolean = false;

	if (! isAuth) {
		return {
			name: 'Login'
		};
	}

	if (nextMiddleware) {
		return nextMiddleware();
	}
}