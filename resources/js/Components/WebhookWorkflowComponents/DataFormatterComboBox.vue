<template>
        <Combobox v-model="selected" @update:model-value="changeEvent(selected)" nullable>
            <div class="relative">
                <div
                    class="relative w-full overflow-hidden rounded-lg bg-white text-left shadow-md focus:outline-none focus-visible:ring-2 focus-visible:ring-white/75 focus-visible:ring-offset-2 focus-visible:ring-offset-teal-300 sm:text-sm">
                    <ComboboxInput
                        class="w-full outline-none border-none py-2 pl-3 pr-10 text-sm leading-5 text-gray-900 focus:ring-0" placeholder="Select Action"
                        :displayValue="(action) => action?.name" @change="query = $event.target.value" />
                    <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                        <ChevronUpDownIcon class="h-5 w-5 text-gray-400" aria-hidden="true" />
                    </ComboboxButton>
                </div>
                <!-- :class="[props.closeWhiteSpace ? 'absolute' : '']" -->
                <TransitionRoot leave="transition ease-in duration-100" leaveFrom="opacity-100" leaveTo="opacity-0"
                    @after-leave="query = ''">
                    <ComboboxOptions
                        class="absolute mt-1 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black/5 focus:outline-none sm:text-sm" :style="{zIndex: 1,minHeight:'120px',maxHeight:'14rem', height : '100%'}">
                        <div v-if="filteredActions.length === 0 && query !== ''"
                            class="relative select-none px-4 py-2 text-gray-700">
                            Nothing found.
                        </div>

                        <ComboboxOption v-for="(action,key) in filteredActions" as="template" :key="key" :value="action"
                            v-slot="{ selected, active }">
                            <li class="relative select-none py-2 pl-10 pr-4" :class="{
                                'bg-teal-600 text-white': active,
                                'text-gray-900': !active,
                            }">
                                <span class="block truncate"
                                    :class="{ 'font-medium': selected, 'font-normal': !selected }">
                                    {{ action.name }}
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

    const actions = [
      { id: 1, name: 'Concat Left', slug: 'concat_left', content: { main_content : 'Add text to the left separated by a glue.',italic_content: '( phoneNumber => +phoneNumber )' }},
      { id: 2, name: 'Concat List Items', slug: 'concat_list_items' , content: { main_content : 'Using a separator, combine particular index values of a list item to produce a single value.' }},
      { id: 3, name: 'Concat Right', slug: 'concat_right' , content: { main_content : 'Add text to the right separated by a glue.',italic_content: '( firstName => firstName lastName )' }},
      { id: 4, name: 'Day Add', slug: 'day_add' , content: { main_content : 'Add one or more day with a date value.',italic_content: '( 2000-01-01 00:00:00 + 30 = 2000-01-31 00:00:00 )' }},
      { id: 5, name: 'Day Subtract', slug: 'day_subtract' , content: { main_content : 'Subtract one or more day from a date value.',italic_content: '( 2000-01-31 00:00:00 -30 = 2000-01-01 00:00:00 )' }},
      { id: 6, name: 'Default Value', slug: 'default_value' , content: { main_content : 'If the value is blank or not found, use a pre-defined value.' }},
      { id: 7, name: 'Format Number', slug: 'format_number' , content: { main_content : 'Use decimal places and separators to format a numerical value.',italic_content: '( 12345.6589 => 12,345.66 )' }},
      { id: 8, name: 'Number Add', slug: 'number_add' , content: { main_content : 'Add a number to a numeric value.',italic_content: '( 999+1000 = 1999 )' }},
      { id: 9, name: 'Number Multiply', slug: 'number_multiply' , content: { main_content : 'Multiply a number with a numeric value.',italic_content: '( 9*10 = 90 )' }},
      { id: 10, name: 'Number Subtract', slug: 'number_subtract' , content: { main_content : 'Subtract a number from a numeric value.',italic_content: '( 1999-1000 = 999 )' }},
      { id: 11, name: 'Replace', slug: 'replace' , content: { main_content : 'Search for a given text in value, then replace every instance with a different value.',italic_content: '( search@search.com => replace@replace.com)' }},
      { id: 12, name: 'Shorten', slug: 'shorten' , content: { main_content : 'To make a value shorter, keep the first few characters and eliminate the rest.',italic_content: '( this is how it works => this is how it... )' }},
      { id: 13, name: 'Split', slug: 'split' , content: { main_content : 'Split a value using a separator to convert it to list and then take a specific index value.',italic_content: '( firstName-middleName-lastName => middleName )' }},
      { id: 14, name: 'Static Value', slug: 'static_value' , content: { main_content : 'Replace the original dynamic value with a pre-defined static value.' }},
      { id: 15, name: 'Trim Left', slug: 'trim_left' , content: { main_content : 'Remove a specific text from the left.',italic_content: '( +phoneNumber => phoneNumber )' }},
      { id: 16, name: 'Trim Right', slug: 'trim_right' , content: { main_content : 'Remove a specific text from the right.',italic_content: '( example@example.com => example )' }},
    ]
    const selectedPerson = ref(actions[0])
    const query = ref('')
    const emit = defineEmits(['dataFormatter'])
  
    const filteredActions = computed(() =>
      query.value === ''
        ? actions
        : actions.filter((action) => {
            return action.name.toLowerCase().includes(query.value.toLowerCase())
          })
    )

    const changeEvent = (action) => {
        emit('dataFormatter', action ?? null);
    }
  </script>