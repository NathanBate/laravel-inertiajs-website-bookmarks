<script setup>
import { computed } from 'vue';
import BreezeButton from '@/Components/BreezeComponents/Button.vue';
import BreezeGuestLayout from '@/Layouts/Guest.vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';

const props = defineProps({
    status: String,
});

const form = useForm();

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(() => props.status === 'verification-link-sent');
</script>

<template>
    <BreezeGuestLayout>
        <template #default>
            <Head title="Email Verification" />

            <div class="mb-4 text-sm text-gray-600 leading-loose text-base sm:text-xl flex flex-col gap-2 sm:gap-4">
                <p>
                    Thanks for signing up and verifying your email!
                </p>
                <p>
                    Please be patient while an administrator approves your profile.
                    Watch for an email announcing that your profile is active.
                </p>
            </div>

        </template>
        <template #requestLogin>
            <div>
                <Link href="/logout">Logout</Link>
            </div>
        </template>
    </BreezeGuestLayout>
</template>
