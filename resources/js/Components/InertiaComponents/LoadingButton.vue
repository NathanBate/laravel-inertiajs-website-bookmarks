<template>
  <button :disabled="loading" class="flex items-center">
      <div v-if="loading" class="italic">Loading</div>
      <slot v-else />
  </button>
</template>

<script>
export default {
  props: {
    loading: Boolean,
  },
}
</script>
