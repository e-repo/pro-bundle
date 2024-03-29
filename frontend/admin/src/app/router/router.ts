import { AuthLayout } from '@/shared/ui/layout';

// import auth from './middleware/auth.ts';
import guest from './middleware/guest.ts';
import { Middleware, MiddlewareContext, MiddlewarePayload } from './middleware/type';
import middlewarePipeline from './middleware/middlewarePaipline.ts';

import {
	createRouter,
	createWebHistory,
	RouteLocationNormalized,
	RouteLocationRaw,
	Router,
	RouteRecordRaw
} from 'vue-router';

const routes: RouteRecordRaw[] = [
	// {
	// 	path: '/',
	// 	name: 'Main',
	// 	component: () => import('@layout/main/main.vue'),
	// 	meta: {
	// 		middleware: [
	// 			auth,
	// 		] as Middleware[]
	// 	}
	// },
	{
		path: '/login',
		name: 'Login',
		component: () => import('@/pages/auth/login'),
		meta: {
			layout: AuthLayout,
			middleware: [
				guest,
			] as Middleware[]
		}
	},
	{
		path: '/recover-password',
		name: 'RecoverPassword',
		component: import('@/pages/auth/restore-password'),
		meta: {
			layout: AuthLayout,
			middleware: [
				guest,
			] as Middleware[]
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
		nextMiddleware: middlewarePipeline(context, middlewares, 1)
	};

	return middlewares[0](payload);
});

export default router;

