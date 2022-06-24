<template>
    <div class="flex">
        <Link href="/">
            <HomeIcon class="w-6 h-auto text-gray-400" />
        </Link>
        <slot/>
    </div>
</template>
<script>
import HomeIcon from "../Icons/Home"
import { Link } from '@inertiajs/inertia-vue3';

export default {
    components: {
        HomeIcon,
        Link
    }
}
</script>
