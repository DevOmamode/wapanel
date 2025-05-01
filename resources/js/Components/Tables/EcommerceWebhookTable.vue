<script setup>
    import axios from 'axios';
    import { ref, computed } from 'vue';
    import debounce from 'lodash/debounce';
    import { router, useForm, usePage } from "@inertiajs/vue3";
    import AlertModal from '@/Components/AlertModal.vue';
    import { useAlertModal } from '@/Composables/useAlertModal';
    import Table from '@/Components/Table.vue';
    import TableHeader from '@/Components/TableHeader.vue';
    import TableHeaderRow from '@/Components/TableHeaderRow.vue';
    import TableHeaderRowItem from '@/Components/TableHeaderRowItem.vue';
    import TableBody from '@/Components/TableBody.vue';
    import TableBodyRow from '@/Components/TableBodyRow.vue';
    import TableBodyRowItem from '@/Components/TableBodyRowItem.vue';
    import { trans } from 'laravel-vue-i18n';
    import Modal from '@/Components/Modal.vue';
    import FormInput from '@/Components/FormInput.vue';
    import FormSelectCombo from '@/Components/Ecommerce/WebhookComboBox.vue';
    import { toast } from 'vue3-toastify';
    const { isOpenAlert, openAlert, confirmAlert } = useAlertModal();
    const props = defineProps(['rows','filters']);    
    const isSearching = ref(false);
    const label = ref('Catalog Webhook');
    const isOpenFormModal = ref(false);
    const formMethod = ref('post');
    const params = ref({
        search: props.filters?.search,
        page: props.filters?.page,
    });
    const search = debounce(() => {
        isSearching.value = true;
        if(!params.value.search) delete params.value.search;
        runSearch();
    }, 1000);
    const runSearch = () => {
        const url = window.location.pathname;
        router.visit(url, {
            method: 'get',
            data: params.value,
        })
    }
    const clearSearch = () => {
        params.value.search = null;
        delete params.value.search;
        runSearch();
    }
    const syncProgress = ref(false);

    const form = useForm({
        page: params.value.page ?? 1,
        eventType:'order_submitted', 
        webhookUrl :'',
        processing: false
    });

    const webhookFormUrl = ref('/store/ecommerce/webhook');
    const openModal = (key) => {
        label.value = trans('Catalog Webhook');
        webhookFormUrl.value = '/store/ecommerce/webhook';
        formMethod.value = 'post';
        if (key) {
            label.value = trans('Catalog Webhook');
            webhookFormUrl.value = '/store/ecommerce/webhook/' + key.id;
            formMethod.value = 'put';
            form.webhookUrl = key.webhookUrl;
            form.eventType = key.eventType;
            isOpenFormModal.value = true;
        } else {
            form.webhookUrl = null;
            form.errors.webhookUrl = null;
            form.errors.eventType = null;
            isOpenFormModal.value = true;
        }
    }

    const submitForm = async () => {
        form.processing = true;
        if(!form.webhookUrl){
            form.errors.webhookUrl = 'This Field is required';
            form.processing = false;
            return;
        }
        if(!isUrlValid(form.webhookUrl)) {
            form.errors.webhookUrl = 'Enter a valid URL';
            form.processing = false;
            return false;
        }
        if(formMethod.value === 'post'){
            form.post(webhookFormUrl.value, {
                onFinish: () => {
                    form.processing = false;
                    isOpenFormModal.value = false;
                }
            });
        } else {
            form.put(formUrl.value, {
                onFinish: () => {
                    isOpenFormModal.value = false;
                }
            });
        }
    }

    const eventTypeSet = (type) => {
        form.eventType = type;
    }

    const removeWebhook = async (uuid) => {
        try {
            form.delete(`/delete/ecommerce/webhook/${uuid}`, {
                onFinish: () => {
                }
            });
        } catch (error) {
            //console.log(error);
        }
    };

    function isUrlValid(userInput) {
        var regexQuery = "^(https?:\\/\\/)?((([-a-z0-9]{1,63}\\.)*?[a-z0-9]([-a-z0-9]{0,253}[a-z0-9])?\\.[a-z]{2,63})|((\\d{1,3}\\.){3}\\d{1,3}))(:\\d{1,5})?((\\/|\\?)((%[0-9a-f]{2})|[-\\w\\+\\.\\?\\/@~#&=])*)?$";
        var url = new RegExp(regexQuery,"i");
        return url.test(userInput);
    }
    
</script>
<template>
    <div class="flex mb-6 mt-6 items-center" :class="[rows.data.length ? 'justify-between' : 'justify-end']">
        <div class="md:bg-white flex items-center border border-primary md:border-none md:shadow-sm h-12 md:h-10 w-full md:w-80 rounded-[0.5rem] text-xl md:text-sm" v-if="rows.data.length">
            <span class="pl-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 15l6 6m-11-4a7 7 0 1 1 0-14a7 7 0 0 1 0 14Z"/></svg>
            </span>
            <input @input="search" v-model="params.search" type="text" class="outline-none px-4 w-full" :placeholder="$t('Search Webhook')">
            <button v-if="isSearching === false && params.search" @click="clearSearch" type="button" class="pr-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10s10-4.5 10-10S17.5 2 12 2zm3.7 12.3c.4.4.4 1 0 1.4c-.4.4-1 .4-1.4 0L12 13.4l-2.3 2.3c-.4.4-1 .4-1.4 0c-.4-.4-.4-1 0-1.4l2.3-2.3l-2.3-2.3c-.4-.4-.4-1 0-1.4c.4-.4 1-.4 1.4 0l2.3 2.3l2.3-2.3c.4-.4 1-.4 1.4 0c.4.4.4 1 0 1.4L13.4 12l2.3 2.3z"/></svg>
            </button>
            <span v-if="isSearching" class="pr-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><circle cx="12" cy="3.5" r="1.5" fill="currentColor" opacity="0"><animateTransform attributeName="transform" calcMode="discrete" dur="2.4s" repeatCount="indefinite" type="rotate" values="0 12 12;90 12 12;180 12 12;270 12 12"/><animate attributeName="opacity" dur="0.6s" keyTimes="0;0.5;1" repeatCount="indefinite" values="1;1;0"/></circle><circle cx="12" cy="3.5" r="1.5" fill="currentColor" opacity="0"><animateTransform attributeName="transform" begin="0.2s" calcMode="discrete" dur="2.4s" repeatCount="indefinite" type="rotate" values="30 12 12;120 12 12;210 12 12;300 12 12"/><animate attributeName="opacity" begin="0.2s" dur="0.6s" keyTimes="0;0.5;1" repeatCount="indefinite" values="1;1;0"/></circle><circle cx="12" cy="3.5" r="1.5" fill="currentColor" opacity="0"><animateTransform attributeName="transform" begin="0.4s" calcMode="discrete" dur="2.4s" repeatCount="indefinite" type="rotate" values="60 12 12;150 12 12;240 12 12;330 12 12"/><animate attributeName="opacity" begin="0.4s" dur="0.6s" keyTimes="0;0.5;1" repeatCount="indefinite" values="1;1;0"/></circle></svg>
            </span>
        </div>
        <button @click="openModal()" class="inline-flex justify-center rounded-md border border-transparent bg-primary px-4 py-2 text-sm text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2">
            <span class="flex gap-1">
                <svg  xmlns="http://www.w3.org/2000/svg"  width="20"  height="20"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-circle-plus"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" /><path d="M9 12h6" /><path d="M12 9v6" /></svg>
                <span>{{ $t('New') }}</span>
            </span>
        </button>
    </div>
    <Table :rows="rows" v-if="rows.data.length">
        <TableHeader>
            <TableHeaderRow>
                <TableHeaderRowItem :position="'first'">{{ $t('S.No') }}</TableHeaderRowItem>
                <TableHeaderRowItem>{{ $t('Webhook Url') }}</TableHeaderRowItem>
                <TableHeaderRowItem>{{ $t('Webhook Type') }}</TableHeaderRowItem>
                <TableHeaderRowItem>{{ $t('Actions') }}</TableHeaderRowItem>
            </TableHeaderRow>
        </TableHeader>
        <TableBody>
            <TableBodyRow v-for="(item, index) in rows.data" :key="index">
                <TableBodyRowItem :position="'first'" class="capitalize">{{ index + 1 }}</TableBodyRowItem>
                <TableBodyRowItem class="hidden sm:table-cell">{{ item.webhook_url }}</TableBodyRowItem>
                <TableBodyRowItem class="hidden sm:table-cell">{{ item.webhook_type }}</TableBodyRowItem>
                <TableBodyRowItem class="hidden sm:table-cell">
                    <span @click="openAlert(item.uuid)" class="block cursor-pointer w-fit">
                        <svg  xmlns="http://www.w3.org/2000/svg"  width="20"  height="20"  viewBox="0 0 24 24"  fill="#b91c1c"  class="icon icon-tabler icons-tabler-filled icon-tabler-trash"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 6a1 1 0 0 1 .117 1.993l-.117 .007h-.081l-.919 11a3 3 0 0 1 -2.824 2.995l-.176 .005h-8c-1.598 0 -2.904 -1.249 -2.992 -2.75l-.005 -.167l-.923 -11.083h-.08a1 1 0 0 1 -.117 -1.993l.117 -.007h16z" /><path d="M14 2a2 2 0 0 1 2 2a1 1 0 0 1 -1.993 .117l-.007 -.117h-4l-.007 .117a1 1 0 0 1 -1.993 -.117a2 2 0 0 1 1.85 -1.995l.15 -.005h4z" /></svg>
                    </span>
                </TableBodyRowItem>
            </TableBodyRow>
        </TableBody>
    </Table>
    <div v-if="rows.data.length == 0 && params.search" class="bg-white rounded-xl">
        <div class="p-4 py-8">
            <div class="flex justify-center mb-4">
                <svg  xmlns="http://www.w3.org/2000/svg"  width="72"  height="72"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-zoom-exclamation"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /><path d="M10 13v.01" /><path d="M10 7v3" /></svg>
            </div>
            <h3 class="text-center text-lg font-medium mb-4">{{ $t('No Webhook Found') }}</h3>
        </div>
    </div>
    <Modal :label="label" :isOpen=isOpenFormModal>
        <div class="mt-5 grid grid-cols-1 gap-x-6 gap-y-4">
            <form @submit.prevent="submitForm()" class="">
                <label for="eventType" class="block text-sm leading-6 text-gray-900">Event Type *</label>
                <FormSelectCombo :eventType="form.eventType" @eventTypeDataForward="eventTypeSet" class="mb-5" id="eventType"/>
                <FormInput v-model="form.webhookUrl" :error="form.errors.webhookUrl" :name="$t('Webhook Url *')" :type="'text'" :class="'sm:col-span-6'" :disabled="formMethod === 'put' ? true : false"/>
                <div class="mt-4 flex">
                    <button type="button" @click.self="isOpenFormModal = false" class="inline-flex justify-center rounded-md border border-transparent bg-slate-50 px-4 py-2 text-sm text-slate-500 hover:bg-slate-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2 mr-4">{{ $t('Cancel') }}</button>
                    <button :class="['inline-flex justify-center rounded-md border border-transparent bg-primary px-4 py-2 text-sm text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2', { 'opacity-50': form.processing }]" :disabled="form.processing">
                        <svg v-if="form.processing" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M12 2A10 10 0 1 0 22 12A10 10 0 0 0 12 2Zm0 18a8 8 0 1 1 8-8A8 8 0 0 1 12 20Z" opacity=".5"/><path fill="currentColor" d="M20 12h2A10 10 0 0 0 12 2V4A8 8 0 0 1 20 12Z"><animateTransform attributeName="transform" dur="1s" from="0 12 12" repeatCount="indefinite" to="360 12 12" type="rotate"/></path></svg>
                        <span v-else>{{ $t('Save') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </Modal>
    <!-- Alert Modal Component-->
    <AlertModal 
        v-model="isOpenAlert" 
        @confirm="() => confirmAlert(removeWebhook)"
        :label = "$t('Delete row')" 
        :description = "$t('Are you sure you want to delete this row? This action can not be undone')"
    />
</template>