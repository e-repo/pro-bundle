interface NavigationParam {
	value: string,
	href: string | null
}

type NavigationKey = 'service_home' | 'service_users' | 'blog_posts' | 'blog_categories';

type NavigationMap = {
	[key in NavigationKey]: NavigationParam
}

const navMap: NavigationMap = {
	'service_home': {value: 'service-home', href: '#'},
	'service_users': {value: 'service-users', href: '#'},
	'blog_posts': {value: 'blog-posts', href: '#'},
	'blog_categories': {value: 'blog-categories', href: '#'},
};

export const getParam = (id: string): NavigationParam => {
	return navMap[id as NavigationKey];
};
