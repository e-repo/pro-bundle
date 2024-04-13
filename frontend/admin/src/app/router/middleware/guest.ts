import { MiddlewarePayload } from './types';
import { RouteLocationRaw } from 'vue-router';
import { useUserModel } from '@/entities/user';

export default function useGuest(payload: MiddlewarePayload): RouteLocationRaw | void {
	const userModel = useUserModel();
	const nextMiddleware = payload.nextMiddleware;

	if (userModel.isAuthenticated) {
		return {
			name: 'Home'
		};
	}

	if (nextMiddleware) {
		return nextMiddleware();
	}
}
