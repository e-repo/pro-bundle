import { AuthLayout } from '@/shared/ui/layout';
import { useAuth, useGuest, useMiddlewarePipeline } from './middleware';
import { Middleware, MiddlewareContext, MiddlewarePayload } from './middleware/types';

import {
	createRouter,
	createWebHistory,
	RouteLocationNormalized,
	RouteLocationRaw,
	Router,
	RouteRecordRaw
} from 'vue-router';

const routes: RouteRecordRaw[] = [
	{
		path: '/',
		name: 'Home',
		component: () => import('@/pages/home'),
		meta: {
			middleware: [
				useAuth,
			] as Middleware[]
		}
	},
	{
		path: '/auth/users',
		name: 'Users',
		component: () => import('@/pages/auth/users'),
		meta: {
			middleware: [
				useAuth,
			] as Middleware[]
		}
	},
	{
		path: '/login',
		name: 'Login',
		component: () => import('@/pages/auth/login'),
		meta: {
			layout: AuthLayout,
			middleware: [
				useGuest,
			] as Middleware[]
		}
	},
	{
		path: '/confirm-reset-password',
		name: 'ConfirmPassword',
		component: () => import('@/pages/auth/confirm-password'),
		meta: {
			layout: AuthLayout,
			middleware: [
				useGuest,
			] as Middleware[]
		}
	},
	{
		path: '/confirm-email',
		name: 'ConfirmEmail',
		component: () => import('@/pages/auth/confirm-email'),
		meta: {
			layout: AuthLayout,
			middleware: [
				useGuest,
			] as Middleware[]
		}
	},
	{
		path: '/request-reset-password',
		name: 'RequestResetPassword',
		component: () => import('@/pages/auth/request-reset-password'),
		meta: {
			layout: AuthLayout,
			middleware: [
				useGuest,
			] as Middleware[]
		}
	},
	{
		path: '/:catchAll(.*)',
		name: 'NotFound',
		component: () => import('@/pages/auth/not-found'),
		meta: {
			layout: AuthLayout,
		}
	},
];

const router: Router = createRouter({
	routes,
	history: createWebHistory(import.meta.env.BASE_URL),
});

router.beforeEach((to: RouteLocationNormalized, from: RouteLocationNormalized): RouteLocationRaw | void => {
	if (! to.meta.middleware) {
		return;
	}

	const middlewares = to.meta.middleware as Middleware[];

	const context: MiddlewareContext = {
		to,
		from,
	};

	const payload: MiddlewarePayload = {
		...context,
		nextMiddleware: useMiddlewarePipeline(context, middlewares, 1)
	};

	return middlewares[0](payload);
});


export default router;

