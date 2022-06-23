<template>
    <div class="w-1/2 flex flex-col">
        <label :for="FieldName" class="py-1">{{ Label }}</label>
        <input
            :id="FieldName"
            :name="FieldName"
            type="text"
            class="h-8 p-2 border border-gray-300 border-solid"
            maxlength="50"
            v-model="form.first_name"
        />
    </div>
</template>

<script>

export default {
    name: 'TextInput',
    props: {
        FieldName: {
            required: true,
            type: String
        },
        Label: {
            required: true,
            type: String
        },
        Error: String,
        MaxLength: {
            default: 50,
            type: Number,
        }
    }
}
</script>
