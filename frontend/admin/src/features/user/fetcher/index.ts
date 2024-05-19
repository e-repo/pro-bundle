import * as UserApi from '../api';
import { UserListFilter } from '@/features/user';

export const UserFetcher = {
	async getUserList(filter: UserListFilter) {
		return await UserApi.fetchUserList(filter);
	}
};
