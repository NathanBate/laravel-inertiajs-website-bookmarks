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
					<BreadCrumb>User Profile</BreadCrumb>
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
                            field-info="If you change your email address, a verification email will be sent to the new address, and it
                                and it will not be active until you verify it."
					/>

                    <div class="hidden sm:block sm:w-1/2 py-2 sm:py-0"/>

				</div>

				<div class="py-4">
					<loading-button :loading="form.processing" class="text-white px-6 py-2" type="submit"
					                style="background-color: #E12D39;">Update
					</loading-button>
				</div>

			</form>

            <form @submit.prevent="submitChangePassword" class="w-full md:3/4 lg:w-2/3 mx-auto mt-12 mb-6">

                <h1 class="text-2xl font-bold uppercase mb-4">Change Password</h1>

                <div class="flex flex-col sm:flex-row gap-0 sm:gap-6 py-2">
                    <FormField
                        field-type="password"
                        class="w-full sm:w-1/2 py-2 sm:py-0"
                        label="Current Password"
                        field-name="currentPassword"
                        v-model:model-value="changePasswordForm.currentPassword"
                        :error="changePasswordForm.errors.currentPassword"
                    />
                    <div class="hidden sm:block sm:w-1/2 py-2 sm:py-0"/>
                </div>
                <div class="flex flex-col sm:flex-row gap-0 sm:gap-6 py-2">
                    <FormField
                        field-type="password"
                        class="w-full sm:w-1/2 py-2 sm:py-0"
                        label="New Password"
                        field-name="newPassword"
                        v-model:model-value="changePasswordForm.newPassword"
                        :error="changePasswordForm.errors.newPassword"
                    />
                    <FormField
                        field-type="password"
                        class="w-full sm:w-1/2 py-2 sm:py-0"
                        label="Confirm New Password"
                        field-name="confirmNewPassword"
                        v-model:model-value="changePasswordForm.newPassword_confirmation"
                        :error="changePasswordForm.errors.newPassword_confirmation"
                    />
                </div>

                <div class="py-4">
                    <loading-button :loading="changePasswordForm.processing" class="text-white px-6 py-2" type="submit"
                                    style="background-color: #E12D39;">Change Password
                    </loading-button>
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
            changePasswordForm: this.$inertia.form({
                currentPassword: '',
                newPassword: '',
                newPassword_confirmation: '',
            }),
		}
	},
	methods: {
		store() {
			this.form.post('/profile/' + this.user.id + '/update')
		},
        submitChangePassword() {
            this.changePasswordForm.post('/profile/' + this.user.id + '/change-password', {
                onSuccess: () => {
                    this.changePasswordForm.currentPassword = ''
                    this.changePasswordForm.newPassword = ''
                    this.changePasswordForm.newPassword_confirmation = ''
                }
            })
        },
	},
	created() {

	}
}
</script>
