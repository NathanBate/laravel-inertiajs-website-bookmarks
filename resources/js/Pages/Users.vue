<template>
    <Layout>
        <Head title="Users" />
        <template v-slot:BreadCrumbs>
            <BreadCrumb>Users</BreadCrumb>
        </template>
        <List :config-prop="vueListConfig" :data-prop="vueListData"/>
    </Layout>
</template>

<script>
import Layout from '@/Layouts/Authenticated.vue';
import { Head, Link } from '@inertiajs/inertia-vue3';
import List from '@nathanbate/vue-list';
import BreadCrumb from '../Components/BreadCrumb'

export default {
    components: {
        Layout,
        Head,
        Link,
        List,
        BreadCrumb
    },
    props: {
        users: Array,
    },
    data() {
        return {
            vueListConfig : {
                showList: true,
                itemsPerPage: 10,
                rowLinkable: true,
                inertiaLinks: true,
                actionButton: {
                    label: 'New User',
                    link: route('users.create')
                },
                columns: [
                    {
                        label: "First Name",
                        link: true,
                        key: "first_name",
                        search: true
                    },
                    {
                        label: "Last Name",
                        link: true,
                        key: "last_name",
                        search: true
                    },
                    {
                        label: "Email",
                        link: true,
                        key: "email",
                        search: true
                    },
                ]
            },
            vueListData: []
        }
    },
    created() {
      if (this.users !== undefined) {
          this.vueListData = this.users
      }
    },
}

</script>
