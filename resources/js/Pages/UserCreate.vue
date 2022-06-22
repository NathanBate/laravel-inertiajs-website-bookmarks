<template>
    <!--
    Good Form Design
    https://medium.com/nextux/form-design-best-practices-9525c321d759
    -->
    <div>
        <Head title="Create User" />
        <h1 class="mb-8 text-3xl font-bold">
            <Link class="text-indigo-400 hover:text-indigo-600" href="/users">Users</Link>
            <span class="text-indigo-400 font-medium">/</span> Create
        </h1>
        <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
            <form @submit.prevent="store">
                <div class="flex flex-col">
                    <label for="first_name">First Name</label>
                    <input id="first_name" v-model="form.first_name" />
                </div>
                <div class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
                    <loading-button :loading="form.processing" class="btn-indigo" type="submit">Create User</loading-button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import { Head, Link } from '@inertiajs/inertia-vue3'
import Layout from '@/Layouts/Authenticated.vue';
import LoadingButton from '@/Shared/LoadingButton'

export default {
    components: {
        Head,
        Link,
        LoadingButton,
    },
    layout: Layout,
    data() {
        return {
            form: {
                first_name: '',
                last_name: '',
                email: '',
                password: '',
            },
        }
    },
    methods: {
        store() {
            this.form.post('/users')
        },
    },
}
</script>
