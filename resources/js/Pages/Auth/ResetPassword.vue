<script setup>
import BreezeButton from '@/Components/BreezeComponents/Button.vue';
import BreezeGuestLayout from '@/Layouts/Guest.vue';
import BreezeInput from '@/Components/BreezeComponents/Input.vue';
import BreezeLabel from '@/Components/BreezeComponents/Label.vue';
import BreezeValidationErrors from '@/Components/BreezeComponents/ValidationErrors.vue';
import { Head, useForm } from '@inertiajs/inertia-vue3';

const props = defineProps({
    email: String,
    token: String,
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.update'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <BreezeGuestLayout>
        <template #default>
            <Head title="Reset Password" />

            <BreezeValidationErrors class="mb-4" />

            <form @submit.prevent="submit">

                <div class="my-2">
                    The password have a minimum of 8 characters, at least one lower
                    case, one upper case, and one number.
                </div>

                <div>
                    <BreezeLabel for="email" value="Email" />
                    <BreezeInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autofocus autocomplete="username" />
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
                    <BreezeButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Reset Password
                    </BreezeButton>
                </div>
            </form>
        </template>
        <template #requestLogin><div></div></template>
    </BreezeGuestLayout>
</template>
