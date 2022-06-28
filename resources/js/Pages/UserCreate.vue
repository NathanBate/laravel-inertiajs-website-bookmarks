<template>

	<!--
			Good Form Design
			https://medium.com/nextux/form-design-best-practices-9525c321d759

			InertiaJS Forms
			https://inertiajs.com/forms#top

	-->

	<Layout>

		<!-- Page Header Items -->
		<Head title="Create User"/>
		<template v-slot:BreadCrumbs>
			<div class="w-full md:3/4 lg:w-2/3 mx-auto">
				<BreadCrumbs>
					<BreadCrumb :href="'/users'">Users</BreadCrumb>
					<BreadCrumb>Create User</BreadCrumb>
				</BreadCrumbs>
			</div>
		</template>

		<!-- Create User Form -->

		<form @submit.prevent="store" class="w-full md:3/4 lg:w-2/3 mx-auto">

			<div class="flex items-center gap-4 bg-gray-50 shadow-lg p-8 my-4">
				<Info class="w-24 text-gray-400"/>
				<p class="font-light leading-loose">An email will be sent to this
					new user for them to set their password. This will also
					confirm their email address.</p>
			</div>

			<div class="flex flex-col sm:flex-row gap-0 sm:gap-6 py-2">
				<FormField
						field-type="textInput"
						class="w-full sm:w-1/2 py-2 sm:py-0"
						label="First Name"
						field-name="first_name"
						v-model:model-value="form.first_name"
						:error="form.errors.first_name"
				/>
				<FormField
						field-type="textInput"
						class="w-full sm:w-1/2 py-2 sm:py-0"
						label="Last Name"
						field-name="last_name"
						v-model:model-value="form.last_name"
						:error="form.errors.last_name"
				/>
			</div>

			<div class="flex flex-col sm:flex-row gap-0 sm:gap-6 py-2">
				<FormField
						class="w-full sm:w-1/2"
						field-type="textInput"
						label="Email"
						field-name="email"
						v-model:model-value="form.email"
						:error="form.errors.email"
				/>
				<FormField
						class="w-full sm:w-1/2 py-2 sm:py-0"
						field-type="select"
						label="Role"
						field-name="role"
						v-model:model-value="form.role"
						:select-options="roleOptions"
						:selected-option="form.role"
						:error="form.errors.role"
						field-info="Admins can manage users. Editors can do anything else but that."
				/>
			</div>

			<div class="py-4">
				<loading-button :loading="form.processing" class="text-white px-6 py-2" type="submit"
				                style="background-color: #E12D39;">Create User
				</loading-button>
			</div>

		</form>
	</Layout>
</template>

<script>
import Layout from '@/Layouts/Authenticated.vue';
import {Head, Link} from '@inertiajs/inertia-vue3'
import LoadingButton from '@/Components/InertiaComponents/LoadingButton'
import BreadCrumbs from '@/Components/BreadCrumbs';
import BreadCrumb from '@/Components/BreadCrumb';
import FormField from '@/Components/FormField';
import Info from '../Icons/Info'

export default {
	components: {
		BreadCrumbs,
		Layout,
		BreadCrumb,
		Head,
		Link,
		LoadingButton,
		FormField,
		Info,
	},
	data() {
		return {
			form: this.$inertia.form({
				first_name: '',
				last_name: '',
				email: '',
				password: '',
				role: ''
			}),
			roleOptions: [
				{label: "", value: ""},
				{label: "Editor", value: "Editor"},
				{label: "Admin", value: "Admin"}
			],
		}
	},
	methods: {
		store() {
			this.form.post('/users')
		},
	},
}
</script>
