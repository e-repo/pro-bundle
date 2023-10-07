import { MiddlewarePayload } from './type';
import { RouteLocationRaw } from 'vue-router';

export default function guest(payload: MiddlewarePayload): RouteLocationRaw | void {
	const nextMiddleware = payload.nextMiddleware;
	const isAuth = false;

	if (isAuth) {
		return {
			name: 'Home'
		};
	}

	if (nextMiddleware) {
		return nextMiddleware();
	}
}