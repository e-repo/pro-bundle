import { defineComponent, reactive, ref } from 'vue';
import { useEmailValidator, usePasswordValidator } from '@layout/login/composables';

interface FormField {
	value: string | number;
	isValid: boolean;
	errorMessage: string
}

export interface LoginForm {
	email: FormField;
	password: FormField;
}

export default defineComponent({
	setup() {
		const loginForm = reactive<LoginForm>({
			email: {
				value: '',
				isValid: true,
				errorMessage: ''
			},
			password: {
				value: '',
				isValid: true,
				errorMessage: ''
			}
		});

		const isPassShow = ref<boolean>(false);
		const login = (): void => {
			checkFormEmpty();

			if (! isFormValid()) {
				return;
			}
		};

		useEmailValidator(loginForm);
		usePasswordValidator(loginForm);

		function checkFormEmpty(): void {
			if (loginForm.email.value === '') {
				loginForm.email.isValid = false;

				loginForm.email.errorMessage = 'Поле email не может быть пустым';
			}

			if (loginForm.password.value === '') {
				loginForm.password.isValid = false;

				loginForm.password.errorMessage = 'Поле password не может быть пустым';
			}
		}

		function isFormValid(): boolean {
			let isFormValid = true;

			Object.values(loginForm).map((field: FormField) => {
				if (! field.isValid) {
					isFormValid = false;
				}
			});

			return isFormValid;
		}

		return {
			loginForm,
			isPassShow,
			login,
		};
	}
});