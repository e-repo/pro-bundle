<template>

	<v-container class="h-100">
		<v-row class="h-100 align-center">
			<v-col class="d-flex justify-center">

				<v-card
					min-width="340"
					width="400"
					elevation="4"
				>
					<v-card-item>
						<v-card-title>
							<h4 class="text-center">Админка</h4>
						</v-card-title>
					</v-card-item>

					<v-divider class="mx-4 mb-2"></v-divider>

					<v-card-text>

						<v-form
							v-model="loginForm.isValid"
							@submit.prevent="onSubmit"
						>

							<v-text-field
								v-model="loginForm.email"
								:rules="[emailRules.required, emailRules.email]"
								type="email"
								label="Email"
								variant="underlined"
							></v-text-field>

							<v-text-field
								v-model="loginForm.password"
								:rules="[passRules.required, passRules.counter]"
								:type="isPassShow ? 'password' : 'text'"
								label="Пароль"
								variant="underlined"
								:append-inner-icon="isPassShow ? 'mdi-eye-off' : 'mdi-eye'"
								counter
								@click:append-inner="isPassShow = !isPassShow"
							></v-text-field>

							<div
								class="d-flex flex-wrap justify-end mt-4"
							>
								<v-btn
									:disabled="!loginForm.isValid"
									:loading="loginForm.loading"
									class="w-25"
									type="submit"
									color="success"
								>
									Вход
								</v-btn>
							</div>

						</v-form>

					</v-card-text>

					<v-divider class="mx-4"></v-divider>

					<v-card-actions
						class="ma-2 flex-column align-start"
					>
						<v-btn
							class="w-100"
							type="button"
							color="primary"
							variant="outlined"
							disabled
						>
							Вход через VK
						</v-btn>
						<p class="mt-2">
							<router-link to="/recover-password" class="text-info">Забыл пароль?</router-link>
						</p>
					</v-card-actions>
				</v-card>

			</v-col>
		</v-row>
	</v-container>

</template>

<script setup lang="ts">
import { RuleType, emailPattern, requiredRule } from '@/shared/lib/form/validation';
import { reactive, ref } from 'vue';

interface LoginForm {
	isValid: boolean;
	email: string | null;
	password: string | null;
	loading: boolean;
}

const loginForm = reactive<LoginForm>({
	isValid: false,
	email: null,
	password: null,
	loading: false,
})

const onSubmit = (): void => {
	if (! loginForm.isValid) {
		return;
	}

	loginForm.loading = true;

	console.log(loginForm);

	setTimeout(() => (loginForm.loading = false), 2000);
};

const isPassShow = ref<boolean>(true);

const emailRules = {
	required: requiredRule,
	email: (value: string): RuleType => emailPattern.test(value) || 'Некорректный \'Email\'',
};

const passRules = {
	required: requiredRule,
	counter: (value: string): RuleType => {
		if (value.length <= 8) {
			return 'Длинна пароля не менее 8-ми символов';
		}

		return value.length <= 20 || 'Максимальное число символов 20';
	}
};
</script>

<style scoped></style>
