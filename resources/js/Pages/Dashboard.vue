<template>
    <Layout>
        <Head title="Dashboard" />
        <template v-slot:BreadCrumbs>
            <BreadCrumbs>
                <BreadCrumb>Home</BreadCrumb>
            </BreadCrumbs>
        </template>

        <div v-if="usersWaitingApproval.length > 0" class="my-8">
            <h1 class="text-2xl font-bold mb-4">Registered Users Waiting Approval</h1>
            <p v-if="usersWaitingApproval.length > 1">When you take action on one user, then next
            one in line will automatically be displayed. Once you have taken action on
            all of them, you will be routed to the user list.</p>
            <List :config-prop="waitingApprovalConfig" :data-prop="usersWaitingApproval" />
        </div>
    </Layout>
</template>
<script>
import Layout from '@/Layouts/Authenticated.vue';
import { Head } from '@inertiajs/inertia-vue3';
import BreadCrumb from '../Components/BreadCrumb'
import BreadCrumbs from '@/Components/BreadCrumbs';
import List from '@nathanbate/vue-list'

export default {
    components: {
        Layout,
        Head,
        BreadCrumbs,
        BreadCrumb,
        List,
    },
    props: {
        usersWaitingApproval: Array,
    },
    data() {
        return {
            waitingApprovalConfig: {
                showHeading: false,
                itemsPerPage: 5,
                searchBar: false,
                inertiaLinks: true,
                rowLinkable: true,
                columns: [
                    { label: 'Name', key: 'name',},
                    { label: 'Email', key: 'email' }
                ]
            }
        }
    }
}
</script>
