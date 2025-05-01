<template>
    <div class="top-16 w-72">
        <Combobox v-model="selected" @update:model-value="changeEvent(selected)">
            <div class="relative mt-1">
                <div
                    class="relative w-full overflow-hidden rounded-lg bg-white text-left shadow-md focus:outline-none focus-visible:ring-2 focus-visible:ring-white/75 focus-visible:ring-offset-2 focus-visible:ring-offset-teal-300 sm:text-sm">
                    <ComboboxInput
                        class="w-full outline-none border-none py-2 pl-3 pr-10 text-sm leading-5 text-gray-900 focus:ring-0" placeholder="Select option"
                        :displayValue="(person) => person.name" @change="query = $event.target.value" />
                    <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                        <ChevronUpDownIcon class="h-5 w-5 text-gray-400" aria-hidden="true" />
                    </ComboboxButton>
                </div>
                <TransitionRoot leave="transition ease-in duration-100" leaveFrom="opacity-100" leaveTo="opacity-0"
                    @after-leave="query = ''">
                    <ComboboxOptions
                        class="absolute mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black/5 focus:outline-none sm:text-sm" style="z-index: 1;">
                        <div v-if="filteredPeople.length === 0 && query !== ''"
                            class="relative select-none px-4 py-2 text-gray-700">
                            Nothing found.
                        </div>

                        <ComboboxOption v-for="(person,key) in filteredPeople" as="template" :key="key" :value="person"
                            v-slot="{ selected, active }" >
                            <li class="relative select-none py-2 pl-10 pr-4" :class="{
                                'bg-teal-600 text-white': active,
                                'text-gray-900': !active,
                            }">
                                <span class="block truncate"
                                    :class="{ 'font-medium': selected, 'font-normal': !selected }">
                                    {{ person.name }}
                                </span>
                                <span v-if="selected" class="absolute inset-y-0 left-0 flex items-center pl-3"
                                    :class="{ 'text-white': active, 'text-teal-600': !active }">
                                    <CheckIcon class="h-5 w-5" aria-hidden="true" />
                                </span>
                            </li>
                        </ComboboxOption>
                    </ComboboxOptions>
                </TransitionRoot>
            </div>
        </Combobox>
    </div>
</template>

<script setup>
    import { ref, computed, onMounted , watchEffect, watch } from 'vue'
    import {
        Combobox,
        ComboboxInput,
        ComboboxButton,
        ComboboxOptions,
        ComboboxOption,
        TransitionRoot,
    } from '@headlessui/vue'
    import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/20/solid'
    const props = defineProps(['templateDetails','index','type','initData']);
    let selected = props.initData ? ref(props.templateDetails.find(data => { return data.value == props.initData})) : props.type == 'subscriber_name' ? ref('') : ref(props.templateDetails[0])
    let query = ref('')
    let filteredPeople = computed(() =>
        query.value === ''
            ? props.templateDetails
            : props.templateDetails.filter((person) =>
                person.name
                    .toLowerCase()
                    .replace(/\s+/g, '')
                    .includes(query.value.toLowerCase().replace(/\s+/g, ''))
            )
    )
    const emit = defineEmits(['templateDataForward'])
    const json = ref({})
    const changeEvent = (person) => {
        json.value = {};
        json.value.index =  props.index;
        json.value.type = props.type;
        json.value.value = person.value;
        emit('templateDataForward',json.value);
        // selected.value = json.value;
    }
    // onMounted(()=>{
    //     setTimeout(()=>{
    //         changeEvent(props.templateDetails[0]);
    //     },500)
    // })
    // watch(() => props.templateDetails, () => {
    //     selected.value = props.templateDetails[0];
    //     changeEvent(props.templateDetails);
    // })
    // watchEffect();
    // changeEvent(props.templateDetails[0]);
</script>