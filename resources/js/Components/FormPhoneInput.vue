<script setup>
    import { ref } from 'vue';
    import 'vue-tel-input/vue-tel-input.css';

    const props = defineProps({
        inputClass: {
            type: String,
            default: "",
        },
        modelValue: [String, Number],
        name: String,
        type: String,
        className: String,
        labelClass: String,
        required: Boolean,
        error: String,
        disabled: Boolean
    })

    const phone = ref(props.modelValue);
    const value = '+' + phone;
    let focusParentClass = ref("");

    const emit = defineEmits(['update:modelValue']);
    const updateValue = (event) => {
        emit('update:modelValue', event.target.value);
    };

    const focusParent = () => {
        focusParentClass.value = `py-2.5 border-green-200 border-2 ring-green-500 ring-2`;
    };

    const focusParentOut = () => {
        focusParentClass.value = "";
    };
</script>
<template>
    <div :class="className">
        <label for="name" class="block text-sm leading-6 text-gray-500" :class="labelClass">{{ name }}</label>
        <div>
            <div style="padding:1px" class="outline-none focus-none focus-within-none block w-full rounded-lg border-2 border-gray-100 text-gray-900 ring-1 ring-gray-300 ring-inset placeholder:text-gray-400 sm:text-sm sm:leading-6" :class="focusParentClass">
                <vue-tel-input 
                    :inputOptions="{
                        autocomplete: 'off'
                    }"
                    v-model="phone"
                    :autoFormat="true"
                    :mode="'international'"
                    :validCharactersOnly="true"
                    @input="updateValue"
                    @focus="focusParent"
                    @blur="focusParentOut"
                    :class="inputClass"
                >
                </vue-tel-input>
            </div>
        </div>
        <div v-if="error" class="form-error text-[#b91c1c] text-xs">{{ error }}</div>
    </div>
</template>