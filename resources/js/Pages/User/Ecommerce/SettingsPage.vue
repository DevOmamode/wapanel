<script setup>
    import axios from 'axios';
    import { ref, computed } from 'vue';
    import debounce from 'lodash/debounce';
    import {Link, router, useForm, usePage } from "@inertiajs/vue3";
    import AlertModal from '@/Components/AlertModal.vue';
    import FormInput from '@/Components/FormInput.vue';
    import FormTextArea from '@/Components/FormTextArea.vue';
    import FormSelect from '@/Components/FormSelect.vue';
    import { useAlertModal } from '@/Composables/useAlertModal';
    import FormCheckbox from '@/Components/FormCheckbox.vue';
    import { toast } from 'vue3-toastify';
    import { trans } from 'laravel-vue-i18n';

    const props = defineProps(['rows','filters','orgProductSettings','orgWhatsappPaySettings','orgPaymentDetailsApi']);
    const fileUrl = props.orgWhatsappPaySettings?.thumbnail_image ? ref(props.orgWhatsappPaySettings?.thumbnail_image) : ref(null); 
    const settingFormType = ref('physical_form');
    const { isOpenAlert, openAlert, confirmAlert } = useAlertModal();
    const removeCatalog = async() => {
        const formData = new FormData();
        await axios.post('/remove/catalog').then(response => {
            window.location.reload();
            if (response.status === 200) {
                toast('Catalog Removed Successfully!', {
                    autoClose: 3000,
                });
            }
        })
    }
    
    const paymentGateways = ref([{label: "Razropay", value:'payment_gateway:razorpay'},{label: "PayUmoney", value:'payment_gateway:payu'}])

    const productForm = useForm({
        flow_id: props.orgProductSettings?.flow_id,
        header : props.orgProductSettings?.header,
        footer : props.orgProductSettings?.footer,
        body : props.orgProductSettings?.body,
        button_text : props.orgProductSettings?.button_text,
        processing:false,
        settingFormType:settingFormType.value,
    });

    const whatsAppPayForm = useForm({
        file: props.orgWhatsappPaySettings?.thumbnail_image,
        body_content : props.orgWhatsappPaySettings?.body_content,
        footer_content : props.orgWhatsappPaySettings?.footer_content,
        payment_gateway: props.orgWhatsappPaySettings?.payment_gateway ?? 'Razropay',
        configuration_name: props.orgWhatsappPaySettings?.configuration_name,
        payment_success_content :  props.orgWhatsappPaySettings?.payment_success_content,
        processing:false,
        settingFormType:settingFormType.value,
    });

    const paymentDetailsAPI = useForm({
        checkEnable: props.orgPaymentDetailsApi?.check_status ?? false,
        apiUrl: props.orgPaymentDetailsApi?.api_url ?? null,
        processing:false,
    });

    const productSubmitForm = () =>{
        productForm.processing = true;
        if(!productForm.header) { productForm.errors.header = 'Header field is Required';} else { productForm.errors.header = '';}
        if(!productForm.flow_id) { productForm.errors.flow_id = 'Flow Id field is Required';} else { productForm.errors.flow_id = '';}
        if(!productForm.footer) { productForm.errors.footer = 'Footer field is Required';} else { productForm.errors.footer = '';}
        if(!productForm.body) { productForm.errors.body = 'Body field is Required';} else { productForm.errors.body = '';}
        if(!productForm.button_text) { productForm.errors.button_text = 'Button Text field is Required';} else { productForm.errors.button_text = '';}
        if (!productForm.header || !productForm.flow_id || !productForm.footer || !productForm.body || !productForm.button_text) {
            productForm.processing = false;
            return false;
        }
        productForm.post('/store/ecommerce/settings', {
            preserveScroll: true,
            onSuccess: () => {
                productForm.errors.header = productForm.errors.flow_id = productForm.errors.footer = productForm.errors.body = productForm.errors.button_text = '';
                productForm.processing = false;
            }
        })
    }

    const whatsAppPaySubmitForm = () =>{
        whatsAppPayForm.processing = true;
        if(!whatsAppPayForm.file) { whatsAppPayForm.errors.file = 'Thumbnail Image field is Required';} else { whatsAppPayForm.errors.file = '';}
        if(!whatsAppPayForm.payment_gateway) { whatsAppPayForm.errors.payment_gateway = 'Payment gateway field is Required';} else { whatsAppPayForm.errors.payment_gateway = '';}
        if(!whatsAppPayForm.footer_content) { whatsAppPayForm.errors.footer_content = 'Footer content field is Required';} else { whatsAppPayForm.errors.footer_content = '';}
        if(!whatsAppPayForm.body_content) { whatsAppPayForm.errors.body_content = 'Body content field is Required';} else { whatsAppPayForm.errors.body_content = '';}
        if(!whatsAppPayForm.configuration_name) { whatsAppPayForm.errors.configuration_name = 'Configuration name field is Required';} else { whatsAppPayForm.errors.configuration_name = '';}
        if(!whatsAppPayForm.payment_success_content) { whatsAppPayForm.errors.payment_success_content = 'Transaction Confirmation Content field is Required';} else { whatsAppPayForm.errors.payment_success_content = '';}
        if (!whatsAppPayForm.file || !whatsAppPayForm.payment_gateway || !whatsAppPayForm.footer_content || !whatsAppPayForm.body_content || !whatsAppPayForm.configuration_name || !whatsAppPayForm.payment_success_content) {
            whatsAppPayForm.processing = false;
            return false;
        }
        whatsAppPayForm.post('/store/whatsapp-pay/settings', {
            preserveScroll: true,
            onSuccess: () => {
                whatsAppPayForm.errors.file = whatsAppPayForm.errors.payment_gateway = whatsAppPayForm.errors.footer_content = whatsAppPayForm.errors.body_content = whatsAppPayForm.errors.configuration_name = whatsAppPayForm.errors.payment_success_content = '';
                whatsAppPayForm.processing = false;
            }
        })
    }

    const apiSubmitForm = () => {
        paymentDetailsAPI.processing = true;
        if(!paymentDetailsAPI.apiUrl) { 
            paymentDetailsAPI.errors.apiUrl = 'API URL field is Required';
            paymentDetailsAPI.processing = false;
            return false;
        } else { 
            paymentDetailsAPI.errors.apiUrl = '';
        }
        if(!isUrlValid(paymentDetailsAPI.apiUrl)) {
            paymentDetailsAPI.errors.apiUrl = 'Enter a valid URL';
            paymentDetailsAPI.processing = false;
            return false;
        }else { 
            paymentDetailsAPI.errors.apiUrl = '';
        }
        paymentDetailsAPI.post('/store/apiurl', {
            preserveScroll: true,
            onSuccess: () => {
                paymentDetailsAPI.processing = false;
            }
        })
    }

    const handleFileUpload = (event) => {
        const fileSizeLimit = 5 * 1024 * 1024; ///5MB
        const file = event.target.files[0];

        if (file && file.size > fileSizeLimit) {
            // Handle file size exceeding the limit
            alert(trans('file size exceeds the limit. Max allowed size:') + ' ' + fileSizeLimit + 'b');
            // Clear the file input
            event.target.value = null;
        } else {
            const reader = new FileReader();

            reader.onload = (e) => {
                fileUrl.value = e.target.result;
            };

            whatsAppPayForm.file = file;

            // Start reading the file
            reader.readAsDataURL(file);
        }
    }

    function isUrlValid(userInput) {
        var regexQuery = "^(https?:\\/\\/)?((([-a-z0-9]{1,63}\\.)*?[a-z0-9]([-a-z0-9]{0,253}[a-z0-9])?\\.[a-z]{2,63})|((\\d{1,3}\\.){3}\\d{1,3}))(:\\d{1,5})?((\\/|\\?)((%[0-9a-f]{2})|[-\\w\\+\\.\\?\\/@~#&=])*)?$";
        var url = new RegExp(regexQuery,"i");
        return url.test(userInput);
    }

</script>
<template>
     <div class="flex mb-6 mt-6 justify-between items-center">
        <div class="flex justify-between text-sm border-b cursor-pointer" style="width: 400px">
            <div class="pt-3 w-1/2 text-center pb-1 hover:bg-slate-50" :class="{ 'bg-gray-50 border-b-2 border-slate-700': settingFormType == 'physical_form' }" @click="settingFormType = 'physical_form'">{{ $t('Physical Product') }}</div>
            <div class="pt-3 w-1/2 text-center pb-1 hover:bg-slate-50" :class="{ 'bg-gray-50 border-b-2 border-slate-700': settingFormType == 'digital_form' }" @click="settingFormType ='digital_form'">{{ $t('Digital Product') }}</div>
        </div>
        <div>
            <button @click="openAlert()" class="rounded-md bg-red-700 px-3 py-2 text-sm text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">{{ $t('Remove Catalog') }}</button>
        </div>
    </div>
    <div class="mb-6 mt-6 w-1/3" style="width: 400px">
        <form @submit.prevent="productSubmitForm()">
            <div class="" v-if="settingFormType == 'physical_form'">
                <FormInput v-model="productForm.header" :error="productForm.errors.header" :name="$t('Header')" :type="'text'" :class="'mb-4'"/>
                <FormTextArea v-model="productForm.body" :error="productForm.errors.body" :name="'Body'" :class="'mb-4'" :textAreaRows="4" />
                <FormInput v-model="productForm.footer" :error="productForm.errors.footer" :name="$t('Footer')" :type="'text'" :class="'mb-4'"/>
                <FormInput v-model="productForm.button_text" :error="productForm.errors.button_text" :name="$t('Button text')" :type="'text'" :class="'mb-4'"/>
                <FormInput v-model="productForm.flow_id" :error="productForm.errors.flow_id" :name="$t('Flow Id')" :type="'text'" :class="'mb-4'"/>
                <div class="mt-4 flex pb-6 border-b justify-end">
                    <button :class="['inline-flex justify-center rounded-md border border-transparent bg-primary px-4 py-2 text-sm text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2', { 'opacity-50': productForm.processing }]" :disabled="productForm.processing">
                        <svg v-if="productForm.processing" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M12 2A10 10 0 1 0 22 12A10 10 0 0 0 12 2Zm0 18a8 8 0 1 1 8-8A8 8 0 0 1 12 20Z" opacity=".5"/><path fill="currentColor" d="M20 12h2A10 10 0 0 0 12 2V4A8 8 0 0 1 20 12Z"><animateTransform attributeName="transform" dur="1s" from="0 12 12" repeatCount="indefinite" to="360 12 12" type="rotate"/></path></svg>
                        <span v-else>{{ $t('Save') }}</span>
                    </button>
                </div>
            </div>
        </form>
        <form @submit.prevent="apiSubmitForm()">
            <div class="">
                <h6 class="text-xl mb-4" :class="{'pt-5' : settingFormType == 'physical_form' }">{{ $t('Pay Details API') }}</h6>
                <FormCheckbox v-model="paymentDetailsAPI.checkEnable" :name="'API Checkbox'" :label="'API URL'" :class="'sm:col-span-2'" :required="paymentDetailsAPI.checkEnable === 1 ? true : false"/>
                <FormInput v-model="paymentDetailsAPI.apiUrl" :error="paymentDetailsAPI.errors.apiUrl" :type="'text'" :disabled="!paymentDetailsAPI.checkEnable"/>
                <div class="mt-4 flex pb-6 border-b justify-end">
                    <button :class="['inline-flex justify-center rounded-md border border-transparent bg-primary px-4 py-2 text-sm text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2', { 'opacity-50': paymentDetailsAPI.processing }]" :disabled="paymentDetailsAPI.processing">
                        <svg v-if="paymentDetailsAPI.processing" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M12 2A10 10 0 1 0 22 12A10 10 0 0 0 12 2Zm0 18a8 8 0 1 1 8-8A8 8 0 0 1 12 20Z" opacity=".5"/><path fill="currentColor" d="M20 12h2A10 10 0 0 0 12 2V4A8 8 0 0 1 20 12Z"><animateTransform attributeName="transform" dur="1s" from="0 12 12" repeatCount="indefinite" to="360 12 12" type="rotate"/></path></svg>
                        <span v-else>{{ $t('Save') }}</span>
                    </button>
                </div>
            </div>
        </form>
        <form @submit.prevent="whatsAppPaySubmitForm()">        
            <div class="">
                <h6 class="text-xl mb-4 pt-5">{{ $t('WhatsApp Pay Settings') }}</h6>
                <div class="mb-4">
                    <label for="name" class="block text-sm leading-6 text-gray-900">Thumbnail Image</label>
                    <div class="flex items-center relative">
                        <div class="rounded-full w-40 m-4" style="margin-left: 0px;margin-bottom: 0px;">
                            <div v-if="fileUrl === null" class="border-2 border-dashed h-16 w-40 flex rounded-lg items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                    <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                                        <path d="M2 6a4 4 0 0 1 4-4h12a4 4 0 0 1 4 4v12a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V6Z"/>
                                        <circle cx="8.5" cy="8.5" r="2.5"/>
                                        <path d="M14.526 12.621L6 22h12.133A3.867 3.867 0 0 0 22 18.133V18c0-.466-.175-.645-.49-.99l-4.03-4.395a2 2 0 0 0-2.954.006Z"/>
                                    </g>
                                </svg>
                            </div>
                            <img v-else class="w-40 h-40 rounded-lg object-cover" :src="fileUrl" alt="Contact Image">
                        </div>
                        <input type="file" class="sr-only" accept="image/*" id="file-upload" @change="handleFileUpload">
                        <label for="file-upload" class="cursor-pointer inline-flex justify-center rounded-md border border-transparent bg-slate-200 px-4 py-2 text-sm text-slate-700 hover:bg-slate-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2 mr-4">{{ $t('Upload image') }}</label>
                    </div>
                    <div v-if="whatsAppPayForm.errors.file" class="form-error text-[#b91c1c] text-xs">{{ whatsAppPayForm.errors.file }}</div>
                </div>
                <FormTextArea v-model="whatsAppPayForm.body_content" :error="whatsAppPayForm.errors.body_content" :name="'Body Content'" :class="'mb-4'" :textAreaRows="4" />
                <FormInput v-model="whatsAppPayForm.footer_content" :error="whatsAppPayForm.errors.footer_content" :name="$t('Footer Content')" :type="'text'" :class="'mb-4'"/>
                <FormSelect v-model="whatsAppPayForm.payment_gateway" :error="whatsAppPayForm.errors.payment_gateway" :options="paymentGateways" :name="$t('Payment Gateway')" :class="'mb-4'" :placeholder="$t('Select Payment Gateway')" :relativeFlag="true"/>
                <FormInput v-model="whatsAppPayForm.configuration_name" :error="whatsAppPayForm.errors.configuration_name" :name="$t('Configuration Name')" :type="'text'" :class="'mb-4'"/>
                <FormTextArea v-model="whatsAppPayForm.payment_success_content" :error="whatsAppPayForm.errors.payment_success_content" :name="'Transaction Confirmation Content'" :class="'mb-4'" :textAreaRows="2" />
                <div class="flex justify-end">
                    <button :class="['inline-flex justify-center rounded-md border border-transparent bg-primary px-4 py-2 text-sm text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2', { 'opacity-50': whatsAppPayForm.processing }]" :disabled="whatsAppPayForm.processing">
                        <svg v-if="whatsAppPayForm.processing" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M12 2A10 10 0 1 0 22 12A10 10 0 0 0 12 2Zm0 18a8 8 0 1 1 8-8A8 8 0 0 1 12 20Z" opacity=".5"/><path fill="currentColor" d="M20 12h2A10 10 0 0 0 12 2V4A8 8 0 0 1 20 12Z"><animateTransform attributeName="transform" dur="1s" from="0 12 12" repeatCount="indefinite" to="360 12 12" type="rotate"/></path></svg>
                        <span v-else>{{ $t('Save') }}</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
    <AlertModal 
        v-model="isOpenAlert" 
        @confirm="() => confirmAlert(removeCatalog)"
        :label = "$t('Delete Catalog')" 
        :description = "$t('Are you sure you want to delete the Catalog? This action can not be undone')"
    />
</template>