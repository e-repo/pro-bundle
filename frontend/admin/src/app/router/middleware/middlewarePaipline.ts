import { Middleware, MiddlewareContext, NextMiddlewareCallable } from './type';

export default function middlewarePipeline(
	context: MiddlewareContext,
	middlewares: Middleware[],
	index: number
): NextMiddlewareCallable | null {
	const nextMiddleware = middlewares[index];

	if (! nextMiddleware) {
		return null;
	}

	return () => {
		const nextPipeline = middlewarePipeline(context, middlewares, index + 1);

		nextMiddleware({ ...context, nextMiddleware: nextPipeline });
	};
}