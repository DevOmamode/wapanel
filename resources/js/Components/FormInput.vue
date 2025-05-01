<script setup>
    import { ref } from 'vue';

    const props = defineProps({
        inputClass: {
            type: String,
            default: "",
        },
        inputIcon: {
            type: String,
            default: "",
        },
        modelValue: [String, Number],
        label: String,
        name: String,
        placeholder: String,
        type: String,
        className: String,
        labelClass: String,
        required: Boolean,
        error: String,
        disabled: Boolean,
        eyeEnable: Boolean,
    })

    const emit = defineEmits(['update:modelValue','viewPassword']);
    let focusParentClass = ref("");
    const iconColor = ref("primary");
    const updateValue = (event) => {
        emit('update:modelValue', event.target.value);
    };
    const viewPasswordFlag = ref(true);
    const viewPassword = (event) => {
        var id = event.currentTarget?.parentElement?.parentElement?.parentElement?.id;
        var node = document.querySelector(`#${id} input`);
        viewPasswordFlag.value = node.getAttribute('type') == 'password' ? false : true
        emit('viewPassword',{id});
    };

    const focusParent = (el) => {
        if (el === 0){
        iconColor.value = "blue-600"; 
        }
        else if (el === 1){
        focusParentClass.value = `border-green-200 border-2 ring-green-500 ring-2`;
        iconColor.value = "blue-600";
        }
    };

    const focusParentOut = (el) => {
        if (el === 0){
        iconColor.value = "primary";
        }
        else if (el === 1){
        focusParentClass.value = "";
        iconColor.value = "primary";
        }
    };
</script>
<template>
    <div :class="className">
        <label for="name" class="block text-sm leading-6 text-gray-500" :class="labelClass">{{ label ?? name }}</label>
        <div class="border-2 border-gray-100 flex gap-1 items-center justify-between outline-none placeholder:text-gray-400 px-4 py-1.5 ring-1 ring-gray-300 ring-inset rounded-lg sm:leading-6 sm:text-sm text-gray-900 w-full relative" :class="focusParentClass" v-if="eyeEnable">
            <input
            class="outline-none w-full pl-2"
            @focus="focusParent(1)"
            @blur="focusParentOut(1)"
            :class="(error ? 'ring-[#b91c1c]' : 'ring-gray-300')+' '+(inputIcon.length > 0?'ml-4':'')+' '+inputClass"
            :type="type"
            :value="props.modelValue"
            @input="updateValue"
            :step="'any'"
            :placeholder="placeholder"
            :disabled="disabled"
            :required="required"
            />
            <div :class="`absolute text-${iconColor} inset-y-0 left-0 pl-3 flex items-center pointer-events-none`" v-html="inputIcon" v-if="inputIcon.length > 0"></div>
            <div class="cursor-pointer">
                <span v-if="viewPasswordFlag" @click="viewPassword" class="relative group">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    <div class="absolute capitalize hidden group-hover:block bg-black text-white text-xs rounded-sm py-1 px-2 bottom-full mb-1 whitespace-no-wrap right-0" style="width: max-content;left: 50%;transform: translate(-50%, 0);">
                        {{ $t('show') }}
                    </div>
                </span>
                <span v-if="!viewPasswordFlag" @click="viewPassword" class="relative group">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye-off"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"  /></svg>
                    <div class="absolute capitalize hidden group-hover:block bg-black text-white text-xs rounded-sm py-1 px-2 bottom-full mb-1 whitespace-no-wrap right-0" style="width: max-content;left: 50%;transform: translate(-50%, 0);">
                        {{ $t('hide') }}
                    </div>
                </span>
            </div>
        </div>
        <div v-else class="relative">
            <input
             class="block w-full rounded-lg border-2 border-gray-100 py-1.5 px-4 text-gray-900 outline-none ring-1 ring-inset placeholder:text-gray-400 sm:text-sm sm:leading-6"
            :class="(error ? 'ring-[#b91c1c]' : 'ring-gray-300')+' '+(inputIcon.length > 0?'pl-10':'')+' '+inputClass"
            :type="type"
            :value="props.modelValue"
            @focus="focusParent(0)"
            @blur="focusParentOut(0)"
            @input="updateValue"
            :step="'any'"
            :placeholder="placeholder"
            :disabled="disabled"
            :required="required"
            />
            <div :class="`absolute text-${iconColor} inset-y-0 left-0 pl-3 flex items-center pointer-events-none`" v-html="inputIcon" v-if="inputIcon.length > 0"></div>
        </div>
        <div v-if="error" class="form-error text-[#b91c1c] text-xs">{{ error }}</div>
    </div>
</template>