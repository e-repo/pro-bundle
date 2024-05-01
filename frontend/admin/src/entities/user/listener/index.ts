import { useUserModel } from '@/entities/user';
import { REFRESH_TOKEN_EVENT_NAME } from '@/shared/api';
import { Emitter }  from '@/shared/lib';
import { useRouter } from 'vue-router';

export const useRefreshTokenListener = () => {
	Emitter.on(REFRESH_TOKEN_EVENT_NAME, async () => {
		const userModel = useUserModel();
		const router = useRouter();

		await userModel.refreshToken();
		router.go(0);
	});
};
