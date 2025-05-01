<template>
    <div class="w-full">
        <label for="name" class="block text-sm leading-6 text-gray-900" :class="labelClass">{{ label ?? name }}</label>
        <Combobox v-model="selected" multiple @update:model-value="changeEvent(selected)">
            <div class="relative mt-1">
                <div
                    class="relative w-full cursor-default overflow-hidden rounded-lg bg-white text-left shadow-md focus:outline-none focus-visible:ring-2 focus-visible:ring-white/75 focus-visible:ring-offset-2 focus-visible:ring-offset-teal-300 sm:text-sm">
                    <ComboboxInput
                        class="w-full border-none py-2 pl-3 pr-10 text-sm leading-5 text-gray-900 focus:ring-0"
                        :displayValue="(contact) => displayValue(contact)" @change="query = $event.target.value" />
                    <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                        <ChevronUpDownIcon class="h-5 w-5 text-gray-400" aria-hidden="true" />
                    </ComboboxButton>
                </div>
                <TransitionRoot leave="transition ease-in duration-100" leaveFrom="opacity-100" leaveTo="opacity-0"
                    @after-leave="query = ''">
                    <ComboboxOptions
                        class="mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black/5 focus:outline-none sm:text-sm" :class="[!position ? 'absolute' : '']">
                        <div v-if="filteredContacts.length === 0 && query !== ''"
                            class="relative cursor-default select-none px-4 py-2 text-gray-700">
                            Nothing found.
                        </div>

                        <ComboboxOption v-for="contact in filteredContacts" as="template" :key="contact.id"
                            :value="contact" v-slot="{ selected, active }">
                            <li class="relative cursor-default select-none py-2 pl-10 pr-4" :class="{
                                'bg-teal-600 text-white': active,
                                'text-gray-900': !active,
                            }">
                                <span class="block truncate"
                                    :class="{ 'font-medium': selected, 'font-normal': !selected }">
                                    {{ (props.type == 'allContactLabels' ? contact.name : contact.full_name) }}
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
    import { ref, computed } from 'vue'
    import {
        Combobox,
        ComboboxInput,
        ComboboxButton,
        ComboboxOptions,
        ComboboxOption,
        TransitionRoot,
    } from '@headlessui/vue'
    import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/20/solid'
    const props = defineProps(['allData','type','selectedData','name','position']);
    const emit = defineEmits(['contactListDataForward'])

    const contacts = props.allData

    let selected = props.selectedData ? ref(props.allData.filter(obj => props.selectedData.includes(obj.id))) : ref([])
    let query = ref('')

    let filteredContacts = computed(() =>
        query.value === ''
            ? contacts
            : contacts.filter((contact) =>
                (props.type == 'allContactLabels' ? contact.name : contact.full_name)
                    .toLowerCase()
                    .replace(/\s+/g, '')
                    .includes(query.value.toLowerCase().replace(/\s+/g, ''))
            )
    )

    let displayValue = (contact) => {
        if (!contact.length) return;
        if (contact.length == 1) return (props.type == 'allContactLabels' ? contact[0].name : contact[0].full_name);
        return contact.reduce((p, c) => {
            return ((props.type == 'allContactLabels' ? p.name : p.full_name) ?? p) + ', ' + ((props.type == 'allContactLabels' ? c.name : c.full_name) ?? c);
        })
    }

    const changeEvent = (eventType) => {
        emit('contactListDataForward', eventType);
    }
</script>