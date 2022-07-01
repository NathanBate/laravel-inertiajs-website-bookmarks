<template>
    <Layout>
        <Head title="Users" />
        <template v-slot:BreadCrumbs>
            <BreadCrumbs>
                <BreadCrumb>Users</BreadCrumb>
            </BreadCrumbs>
        </template>
        <List v-if="user_list" :config-prop="vueListConfig" :data-prop="user_list"/>
    </Layout>
</template>

<script>
import Layout from '@/Layouts/Authenticated.vue';
import { Head, Link } from '@inertiajs/inertia-vue3';
import List from '@nathanbate/vue-list';
import BreadCrumb from '../Components/BreadCrumb'
import BreadCrumbs from '@/Components/BreadCrumbs';

export default {
    components: {
        Layout,
        Head,
        Link,
        List,
        BreadCrumb,
        BreadCrumbs,
    },
    props: {
        user_list: Array,
    },
    data() {
        return {
            vueListConfig : {
                showList: true,
                itemsPerPage: 10,
                rowLinkable: true,
                searchBar: true,
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
	                {
                        label: "Role",
		                link: true,
		                key: 'role',
	                }
                ]
            },
	        vueListData: [],
        }
    },

}

</script>
