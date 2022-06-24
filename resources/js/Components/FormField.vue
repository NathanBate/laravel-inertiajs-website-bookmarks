<template>

    <!--
        Dealing with vmodel updates with an input
        https://vuejs.org/guide/components/events.html#usage-with-v-model
     -->

    <div v-if="fieldType==='textInput'" class="flex flex-col">
        <label :for="fieldName" class="py-1">{{ label }}</label>
        <input
            :id="fieldName"
            :name="fieldName"
            type="text"
            class="h-8 p-2 border border-gray-300 border-solid"
            maxlength="50"
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
        />
        <div v-if="error" class="text-red-500 italic text-base">{{ error }}</div>
    </div>
</template>

<script>

export default {
    name: 'TextInput',
    props: {
        fieldName: {
            required: true,
            type: String
        },
        label: {
            required: true,
            type: String
        },
        error: String,
        maxLength: {
            default: 50,
            type: Number,
        },
        modelValue: String,
        fieldType: {
            required: true,
            type: String
        },
        error: String,
    },
    emits: ['update:modelValue'],
}
</script>
