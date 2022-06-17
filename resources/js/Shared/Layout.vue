<template>
	<div class="w-full h-screen">
		<div class="h-14 w-full flex bg-blue-500">
			<div class="my-auto px-4 gap-2 flex text-white">
        <div class="w-6">
          <Icon class="text-white" name="star" />
        </div>
        <div class="hidden md:block">Website Bookmarks</div>
			</div>
			<div class="flex-1"></div>
			<div class="w-auto my-auto text-white px-4 h-full">
        <Dropdown>
          <template v-slot:ToggleButton>
            <button class="flex bg-transparent h-full">
              <div class="px-2 my-auto">Nathan Bate</div>
              <div class="w-4 h-4 my-auto">
                <Icon name="cheveron-down" />
              </div>
            </button>
          </template>
          <template v-slot:Dropdown>
            <div class="py-2 px-4 text-black w-48">
                <Link href="/users" as="button">
                    Users
                </Link>
                <Link href="/logout" method="post" as="button">
                    Logout
                </Link>
            </div>
          </template>
        </Dropdown>
			</div>
		</div>
		<div class="w-full h-full p-2">
			<slot/>
		</div>
	</div>
</template>

<script>
import Icon from '@/Shared/Icon'
import Dropdown from '@nathanbate/vue-dropdown-menu'
import { Link } from '@inertiajs/inertia-vue3';

export default {
  components: {
    Icon,
    Dropdown,
    Link,
  }
}
</script>
