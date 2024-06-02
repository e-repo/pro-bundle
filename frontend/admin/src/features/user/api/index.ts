import { useHttpBearerToken, tryRefreshToken } from '@/shared/api';
import { useRefreshTokenListener } from '@/entities/user';
import { UserListFilter } from '@/features/user';

const http = useHttpBearerToken();

useRefreshTokenListener();

export const fetchUserList = async (filter: UserListFilter)=> {
	try {
		return (
			await http.get('/auth/v1/user/list', {
				params: filter
			})
		).data;
	} catch (error: unknown) {
		tryRefreshToken(error);
	}
};
