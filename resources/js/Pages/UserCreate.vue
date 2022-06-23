<template>

    <!--
    Good Form Design
    https://medium.com/nextux/form-design-best-practices-9525c321d759
    -->

    <Layout>

        <!-- Page Header Items -->
        <Head title="Create User" />
        <template v-slot:BreadCrumbs>
            <BreadCrumb :href="'/users'">Users</BreadCrumb>
            <BreadCrumb>Create User</BreadCrumb>
        </template>

        <!-- Create User Form -->

        <form @submit.prevent="store">
            <div class="flex flex-col sm:flex-row gap-0 sm:gap-6">


            </div>
            <div class="py-4">
                <loading-button :loading="form.processing" class="text-white px-6 py-2" type="submit" style="background-color: #E12D39;">Create User</loading-button>
            </div>

        </form>


    </Layout>
</template>

<script>
import Layout from '@/Layouts/Authenticated.vue';
import { Head, Link } from '@inertiajs/inertia-vue3'
import LoadingButton from '@/Components/InertiaComponents/LoadingButton'
import BreadCrumb from '@/Components/BreadCrumb';

export default {
    components: {
        Layout,
        BreadCrumb,
        Head,
        Link,
        LoadingButton,
    },
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
