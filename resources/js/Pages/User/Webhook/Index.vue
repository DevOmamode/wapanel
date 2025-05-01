<template>
    <AppLayout>
        <div class="bg-white md:bg-inherit pt-10 px-4 md:pt-8 md:p-8 rounded-[5px] text-[#000] h-full md:overflow-y-auto">
            <div class="flex justify-between">
                <div>
                    <h2 class="text-xl mb-1">{{ $t('WhatsApp Webhook Workflow') }}</h2>
                    <p class="mb-6 flex items-center text-sm leading-6">
                        <span class="ml-1 mt-1">{{ $t('Create and manage your WhatsApp webhook workflows') }}</span>
                    </p>
                </div>
                <div>
                    <button @click="openModal()" class="rounded-md bg-primary px-3 py-2 text-sm text-white shadow-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">{{ $t('Create') }}</button>
                </div>
            </div>
            <WebhookTable :rows="props.rows" @edit="workflowEdit" @copyUrl="copyUrl" @delete="edit = false"  @openModal="openModal" ref="childCompVerifiedRef" />
            <Modal :label="label" :isOpen=isOpenFormModal :description="description">
                <div class="mt-5 grid grid-cols-1 gap-x-6 gap-y-4">
                    <form @submit.prevent="submitForm()" class="grid gap-x-6 gap-y-4 sm:grid-cols-6">
                        <FormInput v-model="form.workflowName" :error="!form.workflowName ? form.errors.workflowName : ''" :name="$t('Workflow Name')" :type="'text'" :class="'sm:col-span-6'" :disabled="formMethod === 'put' ? true : false"/>
                        <FormSelect v-model="form.messageTemplate" :error="!form.messageTemplate ? form.errors.messageTemplate : ''" :options="props.whatsappTemplates" :name="$t('Message Template ')" :class="'sm:col-span-6'" :placeholder="$t('Select Template')" :relativeFlag="true"/>
                        <div class="mt-4 flex">
                            <button type="button" @click.self="isOpenFormModal = false;form.errors.workflowName = '';form.errors.messageTemplate = ''" class="inline-flex justify-center rounded-md border border-transparent bg-slate-50 px-4 py-2 text-sm text-slate-500 hover:bg-slate-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2 mr-4">{{ $t('Cancel') }}</button>
                            <button :class="['inline-flex justify-center rounded-md border border-transparent bg-primary px-4 py-2 text-sm text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2', { 'opacity-50': form.processing }]" :disabled="form.processing">
                                <svg v-if="form.processing" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M12 2A10 10 0 1 0 22 12A10 10 0 0 0 12 2Zm0 18a8 8 0 1 1 8-8A8 8 0 0 1 12 20Z" opacity=".5"/><path fill="currentColor" d="M20 12h2A10 10 0 0 0 12 2V4A8 8 0 0 1 20 12Z"><animateTransform attributeName="transform" dur="1s" from="0 12 12" repeatCount="indefinite" to="360 12 12" type="rotate"/></path></svg>
                                <span v-else>{{ $t('Save') }}</span>
                            </button>
                        </div>
                    </form>
                </div>
            </Modal>
            <WorkflowDetails v-if="edit" :workflowDetail="workflowDetail" :contact="props.contact" :chatLimitReached="isChatLimitReached" :templates="templates" :settings="settings" ref="childCompRef" @copyUrl="copyUrl" @verified_data="verifiedData"></WorkflowDetails>
        </div>
    </AppLayout>
</template>
<script setup>
    import AppLayout from "./../Layout/App.vue";
    import { Link, router , useForm} from "@inertiajs/vue3";
    import { ref, onMounted } from 'vue';
    import { trans } from 'laravel-vue-i18n';
    import axios from "axios";
    import WebhookTable from '@/Components/Tables/WebhookTable.vue';
    import Modal from '@/Components/Modal.vue';
    import FormInput from '@/Components/FormInput.vue';
    import FormSelect from '@/Components/FormSelect.vue';
    import WorkflowDetails from '@/Components/WebhookWorkflowComponents/WorkflowDetails.vue';
    import { toast } from 'vue3-toastify';
    import 'vue3-toastify/dist/index.css';
    const props = defineProps(['rows', 'filters','whatsappTemplates','contact','isChatLimitReached','templates','settings']);
    const isOpenFormModal = ref(false);
    const formUrl = ref('/whatsapp/workflow');
    const formMethod = ref('post');
    const label = ref('Workflow Details');
    const description = ref('Send WhatsApp template message based on data received from 3rd party webhook call');
    const edit = ref(false);
    const workflowDetail = ref(null);
    const copiedRef = ref(null);
    const isChatLimitReached = ref(props.isChatLimitReached);

    const form = useForm({
        workflowName: null,
        messageTemplate: null,
        errors:{
            workflowName:'This Field is required',
            messageTemplate1:'This Field is required',
        }
    });

    const openModal = (key, formData = {}) => {
        label.value = trans('Workflow Details');
        formUrl.value = '/whatsapp/workflow';
        formMethod.value = 'post';

        if (key) {
            label.value = trans('Update user');
            formUrl.value = '/whatsapp/workflow/' + key.id;
            formMethod.value = 'put';
            form.workflowName = key.workflowName;
            form.messageTemplate = key.messageTemplate
            isOpenFormModal.value = true;
        } else {
            form.workflowName = null;
            form.messageTemplate = null;
            form.errors.workflowName = null;
            form.errors.messageTemplate = null;
            isOpenFormModal.value = true;
        }
    }

    const submitForm = () => {
        form.processing = true;
        if(!form.workflowName || !form.messageTemplate ){
            if(!form.workflowName) form.errors.workflowName = 'This Field is required';
            if(!form.messageTemplate) form.errors.messageTemplate = 'This Field is required';
            form.processing = false;
            return false;
        }
        if(formMethod.value === 'post'){
            form.post(formUrl.value, {
                onFinish: (data) => {
                    form.processing = false;
                    isOpenFormModal.value = false;
                    edit.value = false;
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
    const childCompRef = ref(null);
    const workflowEdit = async(data) => {
        edit.value = false;
        setTimeout(()=>{
            workflowDetail.value = data.data;
            edit.value = true;
            childCompRef.value && childCompRef.value.onLoadAndBroadcast();
        })
    };

    const copyUrl = async (url) => {
        copiedRef.value = url;
        const tempInput = document.createElement("textarea");
        tempInput.value = url;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand("copy");
        document.body.removeChild(tempInput);
        setTimeout(() => {
            copiedRef.value = null;
        }, 2000);   
    };

    const childCompVerifiedRef = ref(null);
    const verifiedData = (verified_data) =>{
        childCompVerifiedRef.value && childCompVerifiedRef.value.verified(verified_data);
    }
</script>