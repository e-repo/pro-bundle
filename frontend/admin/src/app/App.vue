<template>
	<component :is="layout">
		<!--    <template v-slot:header>-->
		<!--        <Header />-->
		<!--    </template>-->

		<!--    <template v-slot:navigation>-->
		<!--        <Navigation />-->
		<!--    </template>-->

		<template v-slot:default>
			<router-view />
		</template>

		<!--    <template v-slot:footer>-->
		<!--        <Footer />-->
		<!--    </template>-->
	</component>
</template>


<script setup lang="ts">
import { useRoute } from 'vue-router';
import { computed } from 'vue';
import { DefaultLayout } from '@/shared/ui/layout';

const route = useRoute();

const layout = computed(() => route.meta.layout || DefaultLayout);
</script>

<style></style>
