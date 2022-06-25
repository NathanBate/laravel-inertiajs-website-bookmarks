<script setup>
import BreezeButton from '@/Components/BreezeComponents/Button.vue';
import BreezeGuestLayout from '@/Layouts/Guest.vue';
import BreezeInput from '@/Components/BreezeComponents/Input.vue';
import BreezeLabel from '@/Components/BreezeComponents/Label.vue';
import BreezeValidationErrors from '@/Components/BreezeComponents/ValidationErrors.vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';

const form = useForm({
    first_name: '',
    last_name: '',
    email: '',
    password: '',
    password_confirmation: '',
    terms: false,
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <BreezeGuestLayout>
        <template #default>
            <Head title="Register" />
            <BreezeValidationErrors class="mb-4" />

            <div class="my-4">
                The password have a minimum of 8 characters, at least one lower
                case, one upper case, and one number.
            </div>

            <form @submit.prevent="submit">
                <div>
                    <BreezeLabel for="first_name" value="First Name" />
                    <BreezeInput id="first_name" type="text" class="mt-1 block w-full" v-model="form.first_name" required autofocus autocomplete="first_name" />
                </div>

                <div class="mt-4">
                    <BreezeLabel for="last_name" value="Last Name" />
                    <BreezeInput id="last_name" type="text" class="mt-1 block w-full" v-model="form.last_name" required autocomplete="last_name" />
                </div>

                <div class="mt-4">
                    <BreezeLabel for="email" value="Email" />
                    <BreezeInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autocomplete="username" />
                </div>

                <div class="mt-4">
                    <BreezeLabel for="password" value="Password" />
                    <BreezeInput id="password" type="password" class="mt-1 block w-full" v-model="form.password" required autocomplete="new-password" />
                </div>

                <div class="mt-4">
                    <BreezeLabel for="password_confirmation" value="Confirm Password" />
                    <BreezeInput id="password_confirmation" type="password" class="mt-1 block w-full" v-model="form.password_confirmation" required autocomplete="new-password" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <Link :href="route('login')" class="underline text-sm text-gray-600 hover:text-gray-900">
                        Already registered?
                    </Link>

                    <BreezeButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Register
                    </BreezeButton>
                </div>
            </form>
        </template>
        <template #requestLogin><div></div></template>
    </BreezeGuestLayout>
</template>
