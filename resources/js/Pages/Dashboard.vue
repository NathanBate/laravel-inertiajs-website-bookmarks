<template>
    <Head title="Dashboard" />

    <BreezeAuthenticatedLayout>
        Dashboard
        <br><br>
        {{ role }}
    </BreezeAuthenticatedLayout>
</template>
<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { Head } from '@inertiajs/inertia-vue3';

export default {
    components: {
        BreezeAuthenticatedLayout,
        Head
    },
    props: {
        waitingApproval: Array,
    }
}
</script>
