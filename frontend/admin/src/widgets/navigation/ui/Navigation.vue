<template>

	<v-navigation-drawer
		v-model="navigationModel.drawer"
		:rail="navigationModel.rail"
		permanent
	>
		<v-list-item class="mt-10" prepend-icon="mdi-cog-outline">
			<template v-slot:title>
				<b class="text-decoration-underline">Система:</b>
			</template>
		</v-list-item>

		<v-divider></v-divider>

		<v-list density="compact">
			<v-list-item prepend-icon="mdi-home-silo-outline" title="Главная" value="home"></v-list-item>
			<v-list-item prepend-icon="mdi-account-group-outline" title="Пользователи" value="users"></v-list-item>
		</v-list>



		<v-list-item class="mt-8" prepend-icon="mdi-text-recognition">
			<template v-slot:title>
				<b class="text-decoration-underline">Блог:</b>
			</template>
		</v-list-item>

		<v-divider></v-divider>

		<v-list density="compact" >
			<v-list-item prepend-icon="mdi-post-outline" title="Статьи" value="posts"></v-list-item>
			<v-list-item prepend-icon="mdi-shape-outline" title="Категории" value="categories"></v-list-item>
		</v-list>
	</v-navigation-drawer>

</template>

<script setup lang="ts">
import { useNavigationModel } from '@/entities/navigation';

const navigationModel = useNavigationModel();

</script>

<style scoped></style>
