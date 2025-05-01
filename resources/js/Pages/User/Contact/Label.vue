<template>
    <AppLayout>
        <div class="bg-white md:bg-inherit md:flex md:flex-grow capitalize">
            <div class="md:w-[30%] md:flex flex-col h-full bg-white border-r border-l" :class="label ? 'hidden' : ''">
                <div class="px-4 pt-4">
                    <div class="flex justify-between mt-2">
                        <div class="flex space-x-1 text-xl">
                            <h2>{{ $t('Labels') }}</h2>
                            <span class="text-slate-500 ">{{ props.rowCount }}</span>
                        </div>
                        <div class="flex space-x-2 items-center">
                            <span @click="openModal()" class="cursor-pointer" title="Add Contact">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"><g fill="currentColor" fill-rule="evenodd" clip-rule="evenodd"><path d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10s-4.477 10-10 10S2 17.523 2 12Zm10-8a8 8 0 1 0 0 16a8 8 0 0 0 0-16Z"/><path d="M13 7a1 1 0 1 0-2 0v4H7a1 1 0 1 0 0 2h4v4a1 1 0 1 0 2 0v-4h4a1 1 0 1 0 0-2h-4V7Z"/></g></svg>
                            </span>
                        </div>
                    </div>
                </div>
                <ContactTable :rows="props.rows" :filters="props.filters" :type="'label'" @callback="handleGroup" :allContactLabels="allContactLabels" :contactLabelIds="contactLabelIds"/>
            </div>
            <div class="md:w-[70%] bg-cover md:h-[100vh] flex justify-center overflow-y-scroll ">
                <div v-if="label">
                    <ContactLabelInfo :label="label" />
                    <div class="grid gap-5 mt-3">
                        <MultiSelectComboBox :allData="contact" :type="'contact'" :selectedData="contactLabelIds" @contactListDataForward="contactListDataStore"/>
                        <button @click="storeContact()"  :class="['inline-flex justify-center rounded-md border border-transparent bg-primary px-4 py-2 text-sm text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2', { 'opacity-50': contactStoreForm.processing }]" :disabled="contactStoreForm.processing" style="justify-self: end;">
                        <svg v-if="contactStoreForm.processing" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M12 2A10 10 0 1 0 22 12A10 10 0 0 0 12 2Zm0 18a8 8 0 1 1 8-8A8 8 0 0 1 12 20Z" opacity=".5"/><path fill="currentColor" d="M20 12h2A10 10 0 0 0 12 2V4A8 8 0 0 1 20 12Z"><animateTransform attributeName="transform" dur="1s" from="0 12 12" repeatCount="indefinite" to="360 12 12" type="rotate"/></path></svg>
                        <span v-else>{{ $t('Store Contact') }}</span>
                    </button>
                    </div>
                </div>
                <div v-else class="md:block pt-20 hidden">
                    <div class="border py-10 w-[30em] rounded-xl bg-white">
                        <h2 class="text-center text-2xl text-slate-500 mb-6">{{ $t('Select label') }}</h2>
                        <div class="flex justify-center">
                            <div class="border-r border-slate-500 h-10"></div>
                        </div>
                        <h2 class="text-center text-slate-600">{{ $t('Or') }}</h2>
                        <div class="flex justify-center">
                            <div class="border-r border-slate-500 h-10"></div>
                        </div>
                        <div class="flex justify-center space-x-4 mt-6">
                            <button @click="openModal()" class="bg-primary rounded-lg text-sm text-white p-2 px-8 text-center capitalize">{{ $t('Add label') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
    <!-- Form Modal Component-->
    <FormModal 
        v-model="isOpenFormModal" 
        :label="$t('Add label')" 
        :url="formUrl" 
        :form="form" 
        :formInputs="formInputs"
        @callback="handleCallback"
    />

</template>
<script setup>
    import AppLayout from "./../Layout/App.vue";
    import { ref } from 'vue';
    import { router, useForm, usePage } from "@inertiajs/vue3";
    import ContactLabelInfo from '@/Components/ContactLabelInfo.vue';
    import ContactTable from '@/Components/Tables/ContactTable.vue';
    import Dropdown from '@/Components/Dropdown.vue';
    import DropdownItemGroup from '@/Components/DropdownItemGroup.vue';
    import MultiSelectComboBox from '@/Components/ContactComponents/MultiSelectComboBox.vue';
    import DropdownItem from '@/Components/DropdownItem.vue';
    import FormModal from '@/Components/FormModal.vue';
    import { trans } from 'laravel-vue-i18n';

    const props = defineProps({ rows: Object, filters: Object, rowCount: Number, label: Object, contact:Object, contactLabelIds: Array,allContactLabels:Object });
    const isOpenModal = ref(false);
    const currentUrl = window.location.href;
    const isOpenFormModal = ref(false);
    const formUrl = ref(currentUrl);
    const form = ref({
        name: '',
    });
    const contactStoreForm = useForm({
        labelId: '',
        contactLabelIds: props.contactLabelIds ?? ref([]),
        processing: false
    });

    const initialFormInputs = [
        {
            inputType: 'FormInput',
            name: 'name',
            label: trans('name'),
            type: 'text',
            className: 'sm:col-span-6',
        },
    ];

    const formInputs = initialFormInputs;

    const openModal = () => {
        isOpenFormModal.value = true;
        form.value.name = '';
    }

    const handleGroup = (value) => {
        router.visit('/contact-labels', {
            method: 'get',
            data: value,
        })
    }

    const handleCallback = (res) => {
        list.value = res.data;
        form.value.name = res.data.name;
    }
    
    const contactListDataStore = (array) => {
        contactStoreForm.contactLabelIds = [];
        array.forEach(element => {
            contactStoreForm.contactLabelIds.push(element.id);
        });
    }

    const storeContact = () =>{
        contactStoreForm.processing = true;
        contactStoreForm.labelId = props.label.id;
        contactStoreForm.post('/contact-labels/contact/store', {
            onFinish: () => {
                contactStoreForm.processing = false;
                isOpenFormModal.value = false;
            }
        });
    }

</script>