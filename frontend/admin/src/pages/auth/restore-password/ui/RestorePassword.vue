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
							<h4 class="text-center">Изменение пароля</h4>
						</v-card-title>
					</v-card-item>

					<v-divider class="mx-4 mb-2"></v-divider>

					<v-card-text>

						<v-form
							v-model="restoreForm.isValid"
							@submit.prevent="onSubmit"
						>

							<v-text-field
								v-model="restoreForm.password"
								:rules="[passRules.required, passRules.counter]"
								:type="isPassShow ? 'password' : 'text'"
								label="Пароль"
								variant="underlined"
								:append-inner-icon="isPassShow ? 'mdi-eye-off' : 'mdi-eye'"
								counter
								@click:append-inner="isPassShow = !isPassShow"
							></v-text-field>

							<v-text-field
								v-model="restoreForm.newPassword"
								:rules="[newPassRules.required, newPassRules.counter, newPassRules.equal]"
								:type="isNewPassShow ? 'password' : 'text'"
								label="Новый пароль"
								variant="underlined"
								:append-inner-icon="isNewPassShow ? 'mdi-eye-off' : 'mdi-eye'"
								counter
								@click:append-inner="isNewPassShow = !isNewPassShow"
							></v-text-field>

							<div
								class="d-flex flex-wrap justify-end mt-4"
							>
								<v-btn
									to="/login"
									color="error"
									variant="outlined"
									type="button"
								>
									Вход
								</v-btn>
								<v-btn
									class="ml-2"
									:disabled="!restoreForm.isValid"
									:loading="restoreForm.loading"
									type="submit"
									color="success"
								>
									Изменить пароль
								</v-btn>
							</div>

						</v-form>

					</v-card-text>
				</v-card>

			</v-col>
		</v-row>
	</v-container>

</template>

<script setup lang="ts">
import { RuleType, requiredRule } from '@/shared/lib/form/validation';
import { reactive, ref } from 'vue';

interface RestoreForm {
	isValid: boolean;
	password: string | null;
	newPassword: string | null;
	loading: boolean;
}

const restoreForm = reactive<RestoreForm>({
	isValid: false,
	password: null,
	newPassword: null,
	loading: false,
})

const onSubmit = (): void => {
	if (! restoreForm.isValid) {
		return;
	}

	restoreForm.loading = true;

	console.log(restoreForm);

	setTimeout(() => (restoreForm.loading = false), 2000);
};

const isPassShow = ref<boolean>(true);
const isNewPassShow = ref<boolean>(true);

const passRules = {
	required: requiredRule,
	counter: (value: string): RuleType => {
		if (value.length <= 8) {
			return 'Длинна пароля не менее 8-ми символов';
		}

		return value.length <= 20 || 'Максимальное число символов 20';
	}
};

const newPassRules = {
	required: requiredRule,
	counter: (value: string): RuleType => {
		if (value.length <= 8) {
			return 'Длинна пароля не менее 8-ми символов';
		}

		return value.length <= 20 || 'Максимальное число символов 20';
	},
	equal: (): RuleType =>
		restoreForm.password === restoreForm.newPassword || 'Пароли не совпадают.',
};
</script>

<style scoped></style>
