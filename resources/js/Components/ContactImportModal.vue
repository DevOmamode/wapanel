<script setup>
    import { computed, ref, watch } from 'vue';
    import { Link, router, usePage } from "@inertiajs/vue3";
    import Modal from '@/Components/Modal.vue';
    import { trans } from 'laravel-vue-i18n';
    import MultiSelectComboBox from '@/Components/ContactComponents/MultiSelectComboBox.vue';

    const props = defineProps(['type', 'modelValue','allContactLabels','contactLabelIds']);
    const emit = defineEmits(['update:modelValue']);
    const flashResponse = computed(() => usePage().props.flash.status);
    
    const modalLabel = props.type === 'contact' ? trans('Import contacts') : trans('Import contact groups');
    const isOpenModal = ref(props.modelValue);
    const fileName = ref(null);
    const progressStatus = ref(null);
    const selectedContactLabelIds = ref([]);

    watch(() => props.modelValue, (newValue) => {
        isOpenModal.value = newValue;
    });
    
    const handleFileUpload = () => {
        const file = document.getElementById('file-upload').files[0];
        uploadFile(file);  
    }

    const handleDrop = (event) => {
        event.preventDefault();
        const file = event.dataTransfer.files[0];
        uploadFile(file);  
    };

    function uploadFile(file){
        if (!['text/csv', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'].includes(file.type)) {
            alert(trans('please select a CSV or XLSX file'));
            return;
        }

        fileName.value = file.name;

        const formData = new FormData();
        formData.append('file', file);
        formData.append('selectedContactLabelIds', JSON.stringify(selectedContactLabelIds.value));

        // Use Inertia router to send the POST request
        router.post(props.type === 'contact' ? '/contacts/import' : '/contact-groups/import', formData, {
            forceFormData: true,
            onProgress: event => {
                progressStatus.value = 'pending';
            },
            onSuccess: () => {
                progressStatus.value = 'complete';
            },
            onError: (errors) => {
                progressStatus.value = null;
            }
        });
    }

    function closeModal(){
        isOpenModal.value = false;
        emit('update:modelValue', false);
        setTimeout(() => {
            progressStatus.value = null;
        }, 500);
    }

    const contactListDataStore = (array) => {
        selectedContactLabelIds.value = [];
        array.forEach(element => {
            selectedContactLabelIds.value.push(element.id);
        });
        console.log(selectedContactLabelIds.value);
        
    }
</script>
<template>
    <!-- Import Modal-->
    <Modal :label="modalLabel" :isOpen="isOpenModal">
        <div v-if="type === 'contact'" class="text-sm text-slate-600">{{ $t('Upload a csv/xlsx to import your contact data. For the phone field ensure that you start with the contact\'s country code.') }}</div>
        <div v-else class="text-sm text-slate-600">{{ $t('Upload a csv/xlsx to import your contact groups data.') }}</div>
        <div class="text-sm text-slate-600 underline flex mt-4 mb-6">
            <a :href="type === 'contact' ? '/contacts.xlsx' : '/contact-groups.xlsx'">{{ $t('Click here to download sample template') }}</a>
        </div>
        <div class="max-w-md w-full gap-y-8">
            <div class="space-y-6">
                <MultiSelectComboBox v-if="type === 'contact'" :allData="props.allContactLabels" :name="$t('Label')" :type="'allContactLabels'" :selectedData="contactLabelIds" @contactListDataForward="contactListDataStore" :position="true"/>
                <div v-if="progressStatus == null || progressStatus == 'complete'" @dragover.prevent @drop="handleDrop" class="flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                    <input type="file" class="sr-only" accept=".csv,.xlsx" ref="fileInput" id="file-upload"/>
                    <div class="text-center">
                        <div>
                            <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><g fill="none"><path fill="currentColor" d="m15.393 4.054l-.502.557l.502-.557Zm3.959 3.563l-.502.557l.502-.557Zm2.302 2.537l-.685.305l.685-.305ZM3.172 20.828l.53-.53l-.53.53Zm17.656 0l-.53-.53l.53.53ZM14 21.25h-4v1.5h4v-1.5ZM2.75 14v-4h-1.5v4h2.5Zm18.5-.437V14h2.5v-.437h-1.5ZM14.891 4.61l3.959 3.563l1.003-1.115l-3.958-3.563l-1.004 1.115Zm7.859 8.952c0-1.689.015-2.758-.41-3.714l-1.371.61c.266.598.281 1.283.281 3.104h2.5Zm-3.9-5.389c1.353 1.218 1.853 1.688 2.119 2.285l1.37-.61c-.426-.957-1.23-1.66-2.486-2.79L18.85 8.174ZM10.03 2.75c1.582 0 2.179.012 2.71.216l.538-1.4c-.852-.328-1.78-.316-3.248-.316v1.5Zm5.865.746c-1.086-.977-1.765-1.604-2.617-1.93l-.537 1.4c.532.204.98.592 2.15 1.645l1.004-1.115ZM10 21.25c-1.907 0-3.261-.002-4.29-.14c-1.005-.135-1.585-.389-2.008-.812l-1.06 1.06c.748.75 1.697 1.081 2.869 1.239c1.15.155 2.625.153 4.489.153v-1.5ZM1.25 14c0 1.864-.002 3.338.153 4.489c.158 1.172.49 2.121 1.238 2.87l1.06-1.06c-.422-.424-.676-1.004-.811-2.01c-.138-1.027-.14-2.382-.14-4.289h-1.5ZM14 22.75c1.864 0 3.338.002 4.489-.153c1.172-.158 2.121-.49 2.87-1.238l-1.06-1.06c-.424.422-1.004.676-2.01.811c-1.027.138-2.382.14-4.289.14v1.5ZM21.25 14c0 1.907-.002 3.262-.14 4.29c-.135 1.005-.389 1.585-.812 2.008l1.06 1.06c.75-.748 1.081-1.697 1.239-2.869c.155-1.15.153-2.625.153-4.489h-1.5Zm-18.5-4c0-1.907.002-3.261.14-4.29c.135-1.005.389-1.585.812-2.008l-1.06-1.06c-.75.748-1.081 1.697-1.239 2.869C1.248 6.661 1.25 8.136 1.25 10h2.5Zm7.28-8.75c-1.875 0-3.356-.002-4.511.153c-1.177.158-2.129.49-2.878 1.238l1.06 1.06c.424-.422 1.005-.676 2.017-.811c1.033-.138 2.395-.14 4.312-.14v-1.5Z"/><path stroke="currentColor" stroke-width="1.5" d="M13 2.5V5c0 2.357 0 3.536.732 4.268C14.464 10 15.643 10 18 10h4"/></g></svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                    <span>{{ $t('Click to upload a file') }}</span>
                                </label>
                                <p class="pl-1">{{ $t('Or drag and drop') }}</p>
                            </div>
                            <p class="text-xs text-gray-500">{{ $t('CSV/XLSX files only') }}</p>
                        </div>
                    </div>
                </div>
                <div v-else class="flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                    <div>
                        <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24"><path fill="none" stroke="black" stroke-dasharray="15" stroke-dashoffset="15" stroke-linecap="round" stroke-width="2" d="M12 3C16.9706 3 21 7.02944 21 12"><animate fill="freeze" attributeName="stroke-dashoffset" dur="0.3s" values="15;0"/><animateTransform attributeName="transform" dur="1.5s" repeatCount="indefinite" type="rotate" values="0 12 12;360 12 12"/></path></svg>
                        <div class="text-center mb-2 text-sm text-gray-500">Upload In Progress</div>
                        <div class="rounded-md p-1 bg-slate-50 text-center text-sm">{{ fileName }}</div>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="mt-2" v-if="progressStatus == 'complete'">
                        <div v-if="flashResponse.successfulImports" class="bg-green-50 px-2 py-2 flex rounded-md justify-between items-center mb-1">
                            <div class="mt-1 text-sm">
                                <div class="text-green-800 flex items-center gap-x-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 16 16"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"><path d="M14.25 8.75c-.5 2.5-2.385 4.854-5.03 5.38A6.25 6.25 0 0 1 3.373 3.798C5.187 1.8 8.25 1.25 10.75 2.25"/><path d="m5.75 7.75l2.5 2.5l6-6.5"/></g></svg>
                                    {{ flashResponse.successfulImports + '/' + flashResponse.totalImports }} {{ $t('rows added successfully!') }}
                                </div>
                            </div>
                        </div>
                        <div v-if="flashResponse.failedNames" class="bg-red-50 px-2 py-2 flex rounded-md justify-between items-center mb-1">
                            <div class="mt-1 text-sm">
                                <div class="text-red-600 flex items-center gap-x-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M15.71 8.29a1 1 0 0 0-1.42 0L12 10.59l-2.29-2.3a1 1 0 0 0-1.42 1.42l2.3 2.29l-2.3 2.29a1 1 0 0 0 0 1.42a1 1 0 0 0 1.42 0l2.29-2.3l2.29 2.3a1 1 0 0 0 1.42 0a1 1 0 0 0 0-1.42L13.41 12l2.3-2.29a1 1 0 0 0 0-1.42m3.36-3.36A10 10 0 1 0 4.93 19.07A10 10 0 1 0 19.07 4.93m-1.41 12.73A8 8 0 1 1 20 12a7.95 7.95 0 0 1-2.34 5.66"/></svg>
                                    {{ flashResponse.failedNames }} rows without first name
                                </div>
                            </div>
                        </div>
                        <div v-if="flashResponse.failedDuplicates" class="bg-red-50 px-2 py-2 flex rounded-md justify-between items-center mb-1">
                            <div class="mt-1 text-sm">
                                <div class="text-red-600 flex items-center gap-x-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M15.71 8.29a1 1 0 0 0-1.42 0L12 10.59l-2.29-2.3a1 1 0 0 0-1.42 1.42l2.3 2.29l-2.3 2.29a1 1 0 0 0 0 1.42a1 1 0 0 0 1.42 0l2.29-2.3l2.29 2.3a1 1 0 0 0 1.42 0a1 1 0 0 0 0-1.42L13.41 12l2.3-2.29a1 1 0 0 0 0-1.42m3.36-3.36A10 10 0 1 0 4.93 19.07A10 10 0 1 0 19.07 4.93m-1.41 12.73A8 8 0 1 1 20 12a7.95 7.95 0 0 1-2.34 5.66"/></svg>
                                    {{ flashResponse.failedDuplicates }} duplicates found
                                </div>
                            </div>
                        </div>
                        <div v-if="flashResponse.failedFormats" class="bg-red-50 px-2 py-2 flex rounded-md justify-between items-center mb-1">
                            <div class="mt-1 text-sm">
                                <div class="text-red-600 flex items-center gap-x-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M15.71 8.29a1 1 0 0 0-1.42 0L12 10.59l-2.29-2.3a1 1 0 0 0-1.42 1.42l2.3 2.29l-2.3 2.29a1 1 0 0 0 0 1.42a1 1 0 0 0 1.42 0l2.29-2.3l2.29 2.3a1 1 0 0 0 1.42 0a1 1 0 0 0 0-1.42L13.41 12l2.3-2.29a1 1 0 0 0 0-1.42m3.36-3.36A10 10 0 1 0 4.93 19.07A10 10 0 1 0 19.07 4.93m-1.41 12.73A8 8 0 1 1 20 12a7.95 7.95 0 0 1-2.34 5.66"/></svg>
                                    {{ flashResponse.failedFormats }} formatting issues found
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="progressStatus == null || progressStatus == 'complete'" class="mt-5">
            <div class="flex justify-center gap-4 mt-2 w-full">
                <button type="button" @click="handleFileUpload()" class="w-full inline-flex justify-center rounded-md border border-transparent bg-primary px-4 py-2 text-sm text-white hover:text-slate-500 hover:bg-slate-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2">{{ $t('Import') }}</button>
                <button type="button" @click="closeModal()" class="w-full inline-flex justify-center rounded-md border border-transparent bg-primary px-4 py-2 text-sm text-white hover:text-slate-500 hover:bg-slate-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2">{{ $t('Close') }}</button>
            </div>
        </div>
    </Modal>
</template>