export interface UserListFilter {
	offset: number;
	limit: number;
	firstName?: string;
	lastName?: string;
	email?: string;
	role?: string;
	status?: string;
}

export interface UserProfile {
	id: string;
	email: string;
	firstName: string;
	role: string;
	status: string;
	createdAt: string;
	lastName?: string;
}
