<template>

	<v-container fluid>
		<v-row>
			<v-col>
				<v-breadcrumbs :items="breadcrumbs" class="pl-0"></v-breadcrumbs>
			</v-col>
		</v-row>

		<v-row>
			<v-col>
				<h1>Список пользователей</h1>
			</v-col>
		</v-row>

		<v-row>
			<v-col>
				<v-data-table-server
					:items-per-page="tableOptions.itemsPerPage"
					:items-per-page-options="[tableOptions.itemsPerPage]"
					:headers="tableOptions.headers"
					:items="tableOptions.serverItems"
					:items-length="tableOptions.totalItems"
					:loading="tableOptions.loading"
					:search="tableOptions.search"
					item-value="name"
					@update:options="loadItems"
				>
					<template v-slot:top>

						<div class="d-flex">

							<v-text-field
								v-model="tableOptions.name"
								density="compact"
								class="sort-input mr-2"
								placeholder="Search name..." hide-details
							></v-text-field>
							<v-text-field
								v-model="tableOptions.calories"
								density="compact"
								class="sort-input"
								placeholder="Minimum calories"
								type="number"
								hide-details
							></v-text-field>

						</div>
						<v-divider class="mt-4 mb-4"></v-divider>

					</template>
				</v-data-table-server>
			</v-col>
		</v-row>

	</v-container>

</template>

<script setup lang="ts">
import { onMounted, reactive, ref, watch } from 'vue';
import { EmitterService, List } from '@/shared/lib';
import { useRefreshTokenListener } from '@/entities/user';
import { UserFetcher, UserProfile } from '@/features/user';

useRefreshTokenListener();

type SortItem = { key: string, order?: boolean | 'asc' | 'desc' }

let userList = ref<UserProfile[]>([])

interface LoadParam
{
	page: number,
	itemsPerPage: number,
	sortBy: SortItem[]
}

const breadcrumbs = ref<string[]>(['Система', 'Пользователи']);

const tableOptions = reactive({
	itemsPerPage: 5,
	headers: [
		{ title: 'E-mail', key: 'email', align: 'start', sortable: false },
		{ title: 'Имя', key: 'firstName', align: 'start', sortable: false },
		{ title: 'Роль', key: 'role', align: 'start', sortable: false },
		{ title: 'Статус', key: 'status', align: 'start', sortable: false },
		{ title: 'Дата создания', key: 'createdAt', align: 'start', sortable: false },
	],
	serverItems: userList,
	loading: true,
	totalItems: 0,
	name: '',
	calories: '',
	search: '',
});

const loadItems = async (options: LoadParam): void => {
	tableOptions.loading = true;

	const result = await UserFetcher.getUserList({
		offset: 0,
		limit: tableOptions.itemsPerPage,
	}) as List

	tableOptions.serverItems = <UserProfile[]>result.data;
	tableOptions.totalItems = result.meta.total;
	tableOptions.loading = false;
};

watch(() => tableOptions.name,(): void => {
	tableOptions.search = String(Date.now());
})

watch(() => tableOptions.calories,(): void => {
	tableOptions.search = String(Date.now());
})

onMounted(() => {
	EmitterService.dispatchComponentOnMountedEvent()
});

</script>

<style scoped>

.sort-input {
	max-width: 350px;
}

</style>
