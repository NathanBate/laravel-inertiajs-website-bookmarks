<template>

	<!--
			Good Form Design
			https://medium.com/nextux/form-design-best-practices-9525c321d759

			InertiaJS Forms
			https://inertiajs.com/forms#top

	-->

	<Layout>

		<template #BreadCrumbs>
			<div class="w-full md:3/4 lg:w-2/3 mx-auto">
				<BreadCrumbs>
					<BreadCrumb :href="'/users'">Users</BreadCrumb>
					<BreadCrumb>Update User</BreadCrumb>
				</BreadCrumbs>
			</div>
		</template>

		<template #flashMessages>
			<div class="w-full md:3/4 lg:w-2/3 mx-auto">
				<FlashMessages/>
			</div>
		</template>

		<template #default>

			<!-- Page Header Items -->
			<Head title="Update User"/>

			<!-- Update User Form -->

			<form @submit.prevent="store" class="w-full md:3/4 lg:w-2/3 mx-auto">

                <h1 class="text-2xl font-bold uppercase mt-8 mb-4">Profile Info</h1>

				<div v-if="user.role==='Waiting Approval'" class="flex items-center gap-4 bg-red-100 shadow-lg p-8 my-4">
					<Info class="w-12 text-gray-400"/>
					<p class="font-light leading-loose">This user has requested a
						login, but an administrator has not approved them yet. Change their
						role from "Waiting Approval" to the appropriate role to approve
						them. The user will then be notified of their approvial via
						email.</p>
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
							class="w-full sm:w-1/2 py-2 sm:py-0"
							field-type="textInput"
							label="Email"
							field-name="email"
							v-model:model-value="form.email"
							:error="form.errors.email"
							field-info="It is usually better to have the user change their address on their profile screen so that they can verify the new email address."
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
					                style="background-color: #E12D39;">Update User
					</loading-button>
				</div>

                <div class="mt-12 mb-10">
                    <h1 class="text-2xl font-bold uppercase mb-4">Change Password</h1>
                    <Link
                        class="text-white py-2 text-red-600"
                        :href="'/user/' + user.id + '/send-password-reset-email'"
                    >
                        Send Password Reset Email</Link>
                </div>

				<div class="my-6 text-left sm:text-right text-red-600 italic cursor-pointer">
					<a @click="destroy">Delete User</a>
				</div>
			</form>
		</template>
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
import FlashMessages from '@/Components/InertiaComponents/FlashMessages';

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
		FlashMessages,
	},
	props: {
		user: Object
	},
	data() {
		return {
			form: this.$inertia.form({
				first_name: this.user.first_name,
				last_name: this.user.last_name,
				email: this.user.email,
				role: this.user.role
			}),
			roleOptions: [
				{label: "", value: ""},
                {label: "Disabled", value: "Disabled"},
                {label: "Denied Approval", value: "Denied Approval"},
                {label: "Editor", value: "Editor"},
				{label: "Admin", value: "Admin"}
			],
		}
	},
	methods: {
		store() {
			this.form.post('/user/' + this.user.id + '/update', {
                onSuccess: () => {
                    this.form.first_name = this.user.first_name
                    this.form.last_name = this.user.last_name
                    this.form.email = this.user.email
                    this.form.role = this.user.role
                }
            })
		},
		destroy() {
			if (confirm('Are you sure you want to delete this user? You could set their role to "Disabled" instead.')) {
				this.$inertia.delete(`/users/${this.user.id}`)
			}
		},
	},
	created() {

	},
    updated() {

    }
}
</script>
