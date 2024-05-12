<template>

	<v-container>
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

<!--		<v-row>-->
<!--			<v-col>-->
<!--				<v-data-table-server-->
<!--					v-model:items-per-page="tableParams.itemsPerPage"-->
<!--					:headers="tableParams.headers"-->
<!--					:items="tableParams.serverItems"-->
<!--					:items-length="tableParams.totalItems"-->
<!--					:loading="tableParams.loading"-->
<!--					:search="tableParams.search"-->
<!--					item-value="name"-->
<!--					@update:options="loadItems"-->
<!--				>-->
<!--					<template v-slot:tfoot>-->
<!--						<tr>-->
<!--							<td>-->
<!--								<v-text-field v-model="name" class="ma-2" density="compact" placeholder="Search name..." hide-details></v-text-field>-->
<!--							</td>-->
<!--							<td>-->
<!--								<v-text-field-->
<!--									v-model="calories"-->
<!--									class="ma-2"-->
<!--									density="compact"-->
<!--									placeholder="Minimum calories"-->
<!--									type="number"-->
<!--									hide-details-->
<!--								></v-text-field>-->
<!--							</td>-->
<!--						</tr>-->
<!--					</template>-->
<!--				</v-data-table-server>-->
<!--			</v-col>-->
<!--		</v-row>-->

	</v-container>

</template>

<script setup lang="ts">
import { ref } from 'vue';

const breadcrumbs = ref<string[]>(['Система', 'Пользователи']);

// const tableParams = reactive({
// 	itemsPerPage: 5,
// 	headers: [
// 		{
// 			title: 'Dessert (100g serving)',
// 			key: 'name',
// 			align: 'start',
// 			sortable: false,
// 		},
// 		{ title: 'Calories', key: 'calories', align: 'end' },
// 		{ title: 'Fat (g)', key: 'fat', align: 'end' },
// 		{ title: 'Carbs (g)', key: 'carbs', align: 'end' },
// 		{ title: 'Protein (g)', key: 'protein', align: 'end' },
// 		{ title: 'Iron (%)', key: 'iron', align: 'end' },
// 	],
// 	serverItems: [],
// 	loading: true,
// 	totalItems: 0,
// 	name: '',
// 	calories: '',
// 	search: '',
// });

// const loadItems = ({ page, itemsPerPage, sortBy }) => {
// 	tableParams.loading = true
// 	FakeAPI.fetch({ page, itemsPerPage, sortBy, search: { name: this.name, calories: this.calories } }).then(({ items, total }) => {
// 		tableParams.serverItems = items
// 		tableParams.totalItems = total
// 		tableParams.loading = false
// 	})
// };
//
// const FakeAPI = {
// 	async fetch ({ page, itemsPerPage, sortBy, search }) {
// 		return new Promise(resolve => {
// 			setTimeout(() => {
// 				const start = (page - 1) * itemsPerPage
// 				const end = start + itemsPerPage
// 				const items = desserts.slice().filter(item => {
// 					if (search.name && !item.name.toLowerCase().includes(search.name.toLowerCase())) {
// 						return false
// 					}
//
// 					// eslint-disable-next-line sonarjs/prefer-single-boolean-return
// 					if (search.calories && !(item.calories >= Number(search.calories))) {
// 						return false
// 					}
//
// 					return true
// 				})
//
// 				if (sortBy.length) {
// 					const sortKey = sortBy[0].key
// 					const sortOrder = sortBy[0].order
// 					items.sort((a, b) => {
// 						const aValue = a[sortKey]
// 						const bValue = b[sortKey]
// 						return sortOrder === 'desc' ? bValue - aValue : aValue - bValue
// 					})
// 				}
//
// 				const paginated = items.slice(start, end)
//
// 				resolve({ items: paginated, total: items.length })
// 			}, 500)
// 		})
// 	},
// };

// watch(() => tableParams.name,(): void => {
// 	tableParams.search = String(Date.now());
// })
//
// watch(() => tableParams.calories,(): void => {
// 	tableParams.search = String(Date.now());
// })

// const desserts = [
// 	{
// 		name: 'Frozen Yogurt',
// 		calories: 159,
// 		fat: 6.0,
// 		carbs: 24,
// 		protein: 4.0,
// 		iron: '1',
// 	},
// 	{
// 		name: 'Jelly bean',
// 		calories: 375,
// 		fat: 0.0,
// 		carbs: 94,
// 		protein: 0.0,
// 		iron: '0',
// 	},
// 	{
// 		name: 'KitKat',
// 		calories: 518,
// 		fat: 26.0,
// 		carbs: 65,
// 		protein: 7,
// 		iron: '6',
// 	},
// 	{
// 		name: 'Eclair',
// 		calories: 262,
// 		fat: 16.0,
// 		carbs: 23,
// 		protein: 6.0,
// 		iron: '7',
// 	},
// 	{
// 		name: 'Gingerbread',
// 		calories: 356,
// 		fat: 16.0,
// 		carbs: 49,
// 		protein: 3.9,
// 		iron: '16',
// 	},
// 	{
// 		name: 'Ice cream sandwich',
// 		calories: 237,
// 		fat: 9.0,
// 		carbs: 37,
// 		protein: 4.3,
// 		iron: '1',
// 	},
// 	{
// 		name: 'Lollipop',
// 		calories: 392,
// 		fat: 0.2,
// 		carbs: 98,
// 		protein: 0,
// 		iron: '2',
// 	},
// 	{
// 		name: 'Cupcake',
// 		calories: 305,
// 		fat: 3.7,
// 		carbs: 67,
// 		protein: 4.3,
// 		iron: '8',
// 	},
// 	{
// 		name: 'Honeycomb',
// 		calories: 408,
// 		fat: 3.2,
// 		carbs: 87,
// 		protein: 6.5,
// 		iron: '45',
// 	},
// 	{
// 		name: 'Donut',
// 		calories: 452,
// 		fat: 25.0,
// 		carbs: 51,
// 		protein: 4.9,
// 		iron: '22',
// 	},
// ]

</script>

<style scoped></style>
