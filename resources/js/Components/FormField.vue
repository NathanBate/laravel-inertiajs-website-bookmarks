<template>

    <div>
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
            <div v-if="fieldInfo !== null" class="text-gray-500 italic text-base">{{ fieldInfo }}</div>
            <div v-if="error" class="text-red-500 italic text-base">{{ error }}</div>
        </div>

        <div v-if="fieldType==='password'" class="flex flex-col">
            <label :for="fieldName" class="py-1">
                <span>{{ label }}</span>
                <span><a class="pl-2 text-sm text-gray-500 cursor-pointer" @click="togglePasswordShowHide">{{ passwordFieldShowHideLabel }}</a> </span>
            </label>
            <input
                :id="fieldName"
                :name="fieldName"
                type="password"
                ref="formFieldPasswordField"
                class="h-8 p-2 border border-gray-300 border-solid"
                maxlength="50"
                :value="modelValue"
                @input="$emit('update:modelValue', $event.target.value)"
            />
            <div v-if="fieldInfo !== null" class="text-gray-500 italic text-base">{{ fieldInfo }}</div>
            <div v-if="error" class="text-red-500 italic text-base">{{ error }}</div>
        </div>

        <div v-if="fieldType==='select'" class="flex flex-col">
            <label :for="fieldName" class="py-1">{{ label }}</label>
            <select
                :id="fieldName"
                :name="fieldName"
                class="h-8 p-1 border border-gray-300 border-solid"
                @input="$emit('update:modelValue', $event.target.value)"
            >
                <option
                    class="w-full"
                    v-if="selectOptions !== null"
                    v-for="(option, index) in selectOptions"
                    :key="'select-'+index"
                    :value="option.value"
                    :selected="selectedOption === option.value"
                >{{ option.label }}</option>
            </select>
	        <div v-if="fieldInfo !== null" class="text-gray-500 italic text-base">{{ fieldInfo }}</div>
            <div v-if="error" class="text-red-500 italic text-base">{{ error }}</div>
        </div>
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
        selectOptions: {
            required: false,
            type: Array,
            default: null
        },
        selectedOption: {
            required: false,
            type: String,
            default: ""
        },
        fieldInfo: {
            required: false,
            type: String,
            default: null
        },
    },
    emits: ['update:modelValue'],
    data() {
      return {
          passwordFieldShowHideLabel: 'Show'
      }
    },
    methods: {
        togglePasswordShowHide() {
            if (this.$refs.formFieldPasswordField.type === 'password') {
                this.$refs.formFieldPasswordField.type = 'text'
                this.passwordFieldShowHideLabel = 'Hide'
            } else {
                this.$refs.formFieldPasswordField.type = 'password'
                this.passwordFieldShowHideLabel = 'Show'
            }
        }
    }
}
</script>
