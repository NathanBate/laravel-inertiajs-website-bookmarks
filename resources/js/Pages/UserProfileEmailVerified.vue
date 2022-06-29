<script setup>
import { computed } from 'vue';
import BreezeButton from '@/Components/BreezeComponents/Button.vue';
import BreezeGuestLayout from '@/Layouts/Guest.vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';

const props = defineProps({
	message: String,
});

</script>
<template>
	<BreezeGuestLayout>
		<template #default>
			<Head title="Profile Email Verification" />

			<div class="mb-4 text-sm text-gray-600 leading-loose text-base sm:text-xl flex flex-col gap-2 sm:gap-4">
				<p>
					{{ message }}
				</p>
			</div>

		</template>
		<template #requestLogin>
			<div>
			</div>
		</template>
	</BreezeGuestLayout>
</template>
