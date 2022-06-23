<template>
    <div class="flex text-gray-400 text-base font-normal">
        <NextIcon class="w-3 h-auto text-gray-400 ml-2" />
        <span class="pl-2 inline-block">
            <Link v-if="href !== null" :href="href">
                <h1 class="">
                    <slot/>
                </h1>
            </Link>
            <h1 v-else class="">
                <slot/>
            </h1>
        </span>
    </div>
</template>

<script>
import NextIcon from '../Icons/Next'
import { Link } from '@inertiajs/inertia-vue3';

export default {
    name: 'BreadCrumb',
    components: {
        NextIcon,
        Link
    },
    props: {
        href: {
            default: null,
            required: false,
            type: String
        }

    }
}
</script>
