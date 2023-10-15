import { isReactive, watchEffect } from 'vue';
import { LoginForm } from '@layout/login/login.ts';



export function useEmailValidator(loginFrom: LoginForm): void {
	checkIsReactive(loginFrom);

	const invalidEmailMessage: string = 'Не валидный email';
	const maxEmailMessage: string = 'Длинна email не может быть более 100 символов';

	function checkEmail() {
		loginFrom.email.isValid = true;

		const email = loginFrom.email.value as string;
		const emailTemplate: RegExp = /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(.\w{2,3})+$/;

		if (
			email.length > 0 &&
			null === email.match(emailTemplate)
		) {
			loginFrom.email.isValid = false;
			loginFrom.email.errorMessage = invalidEmailMessage;

			return;
		}

		if (email.length > 100) {
			loginFrom.email.isValid = false;
			loginFrom.email.errorMessage = maxEmailMessage;

			return;
		}
	}

	watchEffect(checkEmail);
}

export function usePasswordValidator(loginFrom: LoginForm): void {
	checkIsReactive(loginFrom);

	const minPasswordMessage: string = 'Длинна пароля не может быть менее 6 символов';
	const maxPasswordMessage: string = 'Длинна пароля не может быть более 32 символов';
	const weakPasswordMessage: string = 'Слабый пароль';

	function checkPassword() {
		loginFrom.password.isValid = true;

		const password = loginFrom.password.value as string;
		const passwordTemplate: RegExp = /^(?=.*[a-zA-Z])(?=.*\d).{6,}$/;

		if (
			password.length > 0 &&
			null === password.match(passwordTemplate)
		) {
			loginFrom.password.isValid = false;
			loginFrom.password.errorMessage = weakPasswordMessage;

			return;
		}

		if (
			password.length > 0 &&
			password.length < 6
		) {
			loginFrom.password.isValid = false;
			loginFrom.password.errorMessage = minPasswordMessage;

			return;
		}

		if (password.length > 32) {
			loginFrom.password.isValid = false;
			loginFrom.password.errorMessage = maxPasswordMessage;

			return;
		}
	}

	watchEffect(checkPassword);
}

function checkIsReactive(loginFrom: LoginForm): void {
	if (! isReactive(loginFrom)) {
		throw Error('\'loginFrom\' must be reactive.');
	}
}