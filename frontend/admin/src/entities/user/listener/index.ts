import { useUserModel } from '@/entities/user';
import { REFRESH_TOKEN_EVENT_NAME } from '@/shared/api';
import { Emitter }  from '@/shared/lib';

export const useRefreshTokenListener = () => {
	Emitter.on(REFRESH_TOKEN_EVENT_NAME, async () => {
		const userModel = useUserModel();

		await userModel.refreshToken();
		location.reload();
	});
};
