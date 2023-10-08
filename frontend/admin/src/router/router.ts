import Main from '@layout/main/main.vue';
import Login from '@layout/login/login.vue';
import RecoverPassword from '@layout/recover-password/recover-password.vue';

import auth from './middleware/auth.ts';
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
	{
		path: '/',
		name: 'Main',
		component: Main,
		meta: {
			middleware: [
				auth,
			] as Middleware[]
		}
	},
	{
		path: '/login',
		name: 'Login',
		component: Login,
		meta: {
			middleware: [
				guest,
			] as Middleware[]
		}
	},
	{
		path: '/recover-password',
		name: 'RecoverPassword',
		component: RecoverPassword,
		meta: {
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

