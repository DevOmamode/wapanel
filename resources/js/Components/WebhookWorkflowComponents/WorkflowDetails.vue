<style>
    .workflowCallbackURL .webhookUrl button{
        transition: opacity 0.5s;
    }

    .workflowCallbackURL:hover .webhookUrl button{
        opacity: 1 !important;
    }

    .border-info {
        border-color: #00cfdd !important;
    }

    .alert-light-info {
        background-color: #ccf5f8;
    }

    .alert-light-warning {
        background-color: #fff8dd;
    }

    .border-warning {
        border-color: #fdac41;
    }

    .text-warning{
        color: #fdac41;
    }

    @media (min-width: 640px) {
        .modalFormGrid {
            grid-template-columns: repeat(12, minmax(0, 1fr));
        }
    }

    @media (min-width: 640px) {
        .singleGrid {
            grid-template-columns: repeat(1, minmax(0, 1fr));
        }
    }
</style>
<script setup>
    import axios from "axios";
    import { Link, router , useForm} from "@inertiajs/vue3";
    import { ref, onMounted ,watchEffect} from 'vue';
    import { trans } from 'laravel-vue-i18n';
    import WebhookTable from '@/Components/Tables/WebhookTable.vue';
    import Modal from '@/Components/Modal.vue';
    import FormInput from '@/Components/FormInput.vue';
    import FormSelect from '@/Components/FormSelect.vue';
    import ChatForm from '@/Components/ChatComponents/ChatForm.vue';
    import FormTextArea from '@/Components/FormTextArea.vue';
    import FormSelectCombo from './WorkflowComboBox.vue';
    import DataFormatterComboBox from '@/Components/WebhookWorkflowComponents/DataFormatterComboBox.vue';
    import { toast } from 'vue3-toastify';
    import 'vue3-toastify/dist/index.css';
    import debounce from 'lodash/debounce';
    import AlertModal from '@/Components/AlertModal.vue';
    import { useAlertModal } from '@/Composables/useAlertModal';
    import Table from '@/Components/Table.vue';
    import TableHeader from '@/Components/TableHeader.vue';
    import TableHeaderRow from '@/Components/TableHeaderRow.vue';
    import TableHeaderRowItem from '@/Components/TableHeaderRowItem.vue';
    import TableBody from '@/Components/TableBody.vue';
    import TableBodyRow from '@/Components/TableBodyRow.vue';
    import TableBodyRowItem from '@/Components/TableBodyRowItem.vue';

    const { isOpenAlert, openAlert, confirmAlert } = useAlertModal();
    import {
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
  } from '@headlessui/vue'
    const props = defineProps(['workflowDetail','chatLimitReached','templates','settings','dataFormatterRows']);
    props.dataFormatterRows = ref([]);
    const textAreaRows = ref(null);
    const recaptureForm = ref(false);
    const saveWorkFlowForm = ref(false);
    const copyText = ref('Copy');
    const isChatLimitReached = ref(props.chatLimitReached);
    const displayTemplate = true;
    const formatterForm = useForm({
        name: null,
        action: null,
        glue: null,
        concat: null,
        position: null,
        day: null,
        default: null,
        decimal: null,
        decimal_separator: null,
        thousand_separator: null,
        number: null,
        search: null,
        replace: null,
        shorten: null,
        separator: null,
        value: null,
        trim: null
    });
    const config = ref(props.settings.metadata);
    const settings = ref(config.value ? JSON.parse(config.value) : null);
    const templateJsonData = props.workflowDetail.workflow_data ? ref(JSON.parse(props.workflowDetail.workflow_data)) : ref([{"type": "phone_number","parameters": []},{ "type": "header", "parameters" : [] }, { "type": "body", "parameters" : []},{"type": "document","parameters": []},{"type": "image","parameters": []}]);
    const isOpenFormModal = ref(false);
    const closeWhiteSpace = ref(true);
    const closeInfoWarning = ref(true);
    const useCaseContent = ref({});
    
    if(templateJsonData.value.findIndex(ele => { return ele.type == 'document' }) == -1){
        templateJsonData.value.push({"type": "document","parameters": []});
    }
    if(templateJsonData.value.findIndex(ele => { return ele.type == 'image' }) == -1){
        templateJsonData.value.push({"type": "image","parameters": []});
    }

    const subscriber_name = ref(props.workflowDetail.map_name) ?? ref('');
    const emit = defineEmits(['copyUrl','verified_data']);
    const selectData = ref([]);
    const phoneNumber = ref();
    const copyUrl = async (url) => {
        emit('copyUrl', url);
        copyText.value = 'Copied!';
        setTimeout(()=>{
            copyText.value = 'Copy';
        },5000)
    };

    const loadRawData = async (uuid) => {
    }
    
    const reCaptureWebhookResponse = async (uuid) => {
        recaptureForm.value = true;
        const formData1 = new FormData();
        formData1.append('uuid', uuid);
        const response = await axios.post('/response/recapture',formData1);
        props.workflowDetail.last_data_mapped = response.data.lastDataMapped ? JSON.stringify(response.data.lastDataMapped, undefined, 4) : '';
        props.workflowDetail.last_data_select = response.data.lastDataMapped;
        props.workflowDetail.workflow_data = response.data.workflowData;
        if(response.data.diffArray || !response.data.workflowData){
            templateJsonData.value = templateJsonData.value;
        }else{
            templateJsonData.value = JSON.parse(response.data.workflowData);
        }
        selectData.value = [];
        if(props.workflowDetail.last_data_select && (response.data.diffArray || !response.data.workflowData)){
            recursiveObjectEntries(props.workflowDetail.last_data_select, (path, value) => {
                selectData.value.push({ name: path.join(' > ') + " : " + value, value: path.join('.') });
            });
            init();
        }
        recaptureForm.value = false
        toast('Response Re-Captured Successfully!', {
            autoClose: 3000,
        });
        return true;
    }

    const onLoadAndBroadcast = () => {
        document.querySelector('.workflowDetils').scrollIntoView({behavior:"smooth",block:"center",inline:"nearest"}); 
    }

    const matchUrl = (metadata,type = '') => {
        const regex = /{{(.*?)}}/g;
        const matches = [];
        let match;
        if(type == 'header'){
            let headerJson = JSON.parse(metadata).components.filter((ele)=>{return ele.type == "HEADER" && ele.format == "TEXT"});
            if(!headerJson.length) return matches;
            if(headerJson.length){
                while ((match = regex.exec(headerJson[0].text)) !== null) {
                    matches.push({data : match[1].trim(), type: 'header'}); // match[1] contains the content inside the braces
                }
            }
            return matches;
        }else if (type == 'document') {
            let documentJson = JSON.parse(metadata).components.filter((ele)=>{return ele.format == "DOCUMENT"});
            if(!documentJson.length) return matches;
            if(documentJson.length){
                documentJson.forEach(ele=>{
                    matches.push({data : ele.format.trim(), type: 'document'}); 
                })
            }
            return matches;
        }else if (type == 'image') {
            let imageJson = JSON.parse(metadata).components.filter((ele)=>{return ele.format == "IMAGE"});
            if(!imageJson.length) return matches;
            if(imageJson.length){
                imageJson.forEach(ele=>{
                    matches.push({data : ele.format.trim(), type: 'image'}); 
                })
            }
            return matches;
        }
        else{
            let bodyJson = JSON.parse(metadata).components.filter((ele)=>{return ele.type == "BODY"});
            if(!bodyJson.length) return matches;
            if(bodyJson.length){
                while ((match = regex.exec(bodyJson[0].text)) !== null) {
                    matches.push({data : match[1].trim(), type: 'body'}); // match[1] contains the content inside the braces
                }
            }
            return matches;
        }
    }

    const templateDataForward = (json) => {
        if(json.type == 'subscriber_name') {
            subscriber_name.value = json.value;
            return true;
        }
        var inx = templateJsonData.value.findIndex(e=>{ return e.type == json.type });
        if(inx != -1){
            templateJsonData.value[inx].parameters[json.index] = json.value;
        }
    }

    const init = () => {
        if(!props.workflowDetail.workflow_data && props.workflowDetail.last_data_select){
            var header = matchUrl(props.workflowDetail.metadata,'header');
            if(header.length){
                header.forEach((ele,inx)=>{
                    var json = {index:inx,type:ele.type,value:selectData.value[0].value};
                    templateDataForward(json);
                })
            }
            var body = matchUrl(props.workflowDetail.metadata,'body');
            if(body.length){
                body.forEach((ele,inx)=>{
                    var json = {index:inx,type:ele.type,value:selectData.value[0].value};
                    templateDataForward(json);
                })
            }
            var document = matchUrl(props.workflowDetail.metadata,'document');
            if(document.length){
                document.forEach((ele,inx)=>{
                    let arr = ['.pdf', '.txt', '.ppt', '.doc', '.xls', '.docx', '.pptx', '.xlsx'];
                    let filterData = selectData.value.filter(e => {return arr.filter(ele => {return e.name.includes(ele)}).length})
                    var json = {index:inx,type:'document',value: filterData.length ? filterData[0].value : selectData.value[0].value};
                    templateDataForward(json);
                })
            }
            var image = matchUrl(props.workflowDetail.metadata,'image');
            if(image.length){
                image.forEach((ele,inx)=>{
                    let arr = ['.png', '.jpg'];
                    let filterData = selectData.value.filter(e => {return arr.filter(ele => {return e.name.includes(ele)}).length})
                    var json = {index:inx,type:'image',value:filterData.length ? filterData[0].value : selectData.value[0].value};
                    templateDataForward(json);
                })
            }
            if(props.workflowDetail.last_data_select){
                recursiveObjectEntries(props.workflowDetail.last_data_select, (path, value) => {
                    console.log(`${path.join('.')} = ${value}`);
                    if(value && phonenumberRegex(value)){
                        phoneNumber.value = path.join('.');
                    }
                });
            }
            if(phoneNumber.value){
                var json = {index:0,type:'phone_number',value:phoneNumber.value};
                templateDataForward(json);
            }else{
                var json = {index:0,type:'phone_number',value:selectData.value[0].value};
                templateDataForward(json);
            }
        }
    }

    function recursiveObjectEntries(obj, callback, path = []) {
        for (const [key, value] of Object.entries(obj)) {
            const currentPath = [...path, key];
            
            if (value && typeof value === "object" && !Array.isArray(value)) {
                // If value is an object, call recursively
                recursiveObjectEntries(value, callback, currentPath);
            } else {
                // If value is not an object, apply the callback
                callback(currentPath, value);
            }
        }
    }

    if(props.workflowDetail.last_data_select){
        recursiveObjectEntries(props.workflowDetail.last_data_select, (path, value) => {
            selectData.value.push({ name: path.join(' > ') + " : " + value, value: path.join('.') });
        });
    }

    const saveWorkFlow = async (uuid) => {
        saveWorkFlowForm.value = true;
        const formData2 = new FormData();
        formData2.append('uuid', uuid);
        formData2.append('workflow_json', JSON.stringify(templateJsonData.value));
        formData2.append('subscriber_name', subscriber_name.value ?? '');
        const response = await axios.post('/workflow/store',formData2);
        saveWorkFlowForm.value = false
        if (response.status === 200){
            emit('verified_data',{'workflowDetail':response.data.workflowDetail,'uuid':uuid});
        }
        // props.workflowDetail.last_data_mapped = response.data.lastDataMapped ? JSON.stringify(response.data.lastDataMapped, undefined, 4) : '';
        toast('Workflow Saved Successfully!', {
            autoClose: 3000,
        });
    }

    function phonenumberRegex(str) {
        str = typeof str == 'string' ? str.trim() : str;
        var a = /^[+]{1}(?:[0-9\-\\(\\)\\/.]\s?){6,15}[0-9]{1}$/.test(str) || /^(1\s|1|)?((\(\d{3}\))|\d{3})(\-|\s)?(\d{3})(\-|\s)?(\d{4})$/.test(str);
        return a;
    }

    const workflowMessageInitData = (json,index, type) => {
        var data = json.find(e => { return e.type == type});
        if(!data || (type == 'phone_number' && !props.workflowDetail.workflow_data)){
            if(props.workflowDetail.last_data_select){
                recursiveObjectEntries(props.workflowDetail.last_data_select, (path, value) => {
                    if(value && phonenumberRegex(value)){
                        phoneNumber.value = path.join('.');
                    }
                });
            }
            return phoneNumber.value;
        }else if(type == 'document'){
            var document = matchUrl(props.workflowDetail.metadata,'document');
            if(document.length){
                document.forEach((ele,inx)=>{
                    let arr = ['.pdf', '.txt', '.ppt', '.doc', '.xls', '.docx', '.pptx', '.xlsx'];
                    let filterData = selectData.value.filter(e => {return arr.filter(ele => {return e.name.includes(ele)}).length})
                    return filterData.length ? filterData[0].value : data.parameters[index];
                })
            }
        }else if(type == 'image'){
            var image = matchUrl(props.workflowDetail.metadata,'image');
            if(image.length){
                image.forEach((ele,inx)=>{
                    let arr = ['.png', '.jpg'];
                    let filterData = selectData.value.filter(e => {return arr.filter(ele => {return e.name.includes(ele)}).length})
                    return filterData.length ? filterData[0].value : data.parameters[index];
                })
            }
        }
        return data.parameters[index];
    }

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

    const submitForm = () => {
        formatterForm.processing = true;
        if(!formatterForm.name){ formatterForm.errors.name = 'Label Field is required';formatterForm.processing = false; }
        if(!formatterForm.action){ formatterForm.errors.action = 'Contact Field is required';formatterForm.processing = false; }
        // if(!formatterForm.glue){ formatterForm.errors.glue = 'Contact Field is required';formatterForm.processing = false; }
        // if(!formatterForm.concat){ formatterForm.errors.concat = 'Contact Field is required';formatterForm.processing = false; }
        // if(!formatterForm.position){ formatterForm.errors.position = 'Contact Field is required';formatterForm.processing = false; }
        // if(!formatterForm.day){ formatterForm.errors.day = 'Contact Field is required';formatterForm.processing = false; }
        // if(!formatterForm.default){ formatterForm.errors.default = 'Contact Field is required';formatterForm.processing = false; }
        // if(!formatterForm.decimal){ formatterForm.errors.decimal = 'Contact Field is required';formatterForm.processing = false; }
        // if(!formatterForm.decimal_separator){ formatterForm.errors.decimal_separator = 'Contact Field is required';formatterForm.processing = false; }
        // if(!formatterForm.thousand_separator){ formatterForm.errors.thousand_separator = 'Contact Field is required';formatterForm.processing = false; }
        // if(!formatterForm.number){ formatterForm.errors.number = 'Contact Field is required';formatterForm.processing = false; }
        // if(!formatterForm.search){ formatterForm.errors.search = 'Contact Field is required';formatterForm.processing = false; }
        // if(!formatterForm.replace){ formatterForm.errors.replace = 'Contact Field is required';formatterForm.processing = false; }
        // if(!formatterForm.shorten){ formatterForm.errors.shorten = 'Contact Field is required';formatterForm.processing = false; }
        // if(!formatterForm.separator){ formatterForm.errors.separator = 'Contact Field is required';formatterForm.processing = false; }
        // if(!formatterForm.value){ formatterForm.errors.value = 'Contact Field is required';formatterForm.processing = false; }
        // if(!formatterForm.trim){ formatterForm.errors.trim = 'Contact Field is required';formatterForm.processing = false; }
        if(!formatterForm.name || !formatterForm.action) { return false; }else{ formatterForm.errors.name = formatterForm.errors.action = formatterForm.errors.glue = formatterForm.errors.concat = formatterForm.errors.position = formatterForm.errors.day = formatterForm.errors.default = formatterForm.errors.decimal = formatterForm.errors.decimal_separator = formatterForm.errors.thousand_separator = formatterForm.errors.number = formatterForm.errors.search = formatterForm.errors.replace = formatterForm.errors.shorten = formatterForm.errors.separator = formatterForm.errors.value = formatterForm.errors.trim = ''; }
        formatterForm.post('/workflow/dataformatter/store', {
            preserveScroll: true,
            onSuccess: () => {
                formatterForm.processing = false;
                // router.visit('/automation/ai', {
                //     preserveState: false,
                // });
            }
        })
    }
    
    const dataFormatter = (formatterType) => {
        formatterForm.action = formatterType ? formatterType.slug : null;
        useCaseContent.value = formatterType ? formatterType.content : {};
        formatterForm.glue = formatterForm.concat = formatterForm.position = formatterForm.day = formatterForm.default = formatterForm.decimal = formatterForm.decimal_separator = formatterForm.thousand_separator = formatterForm.number = formatterForm.search = formatterForm.replace = formatterForm.shorten = formatterForm.separator = formatterForm.value = formatterForm.trim = null;
    }

    const deleteDataFormatterAction = async (uuid) => {
        try {
            const response = await axios.delete(`/workflow/dataformatter/${uuid}`);

            if (response.status === 200 && response.data.success) {
                const idx = props.dataFormatterRows.data.findIndex((i) => i.uuid === uuid);
                props.dataFormatterRows.data.splice(idx, 1);
            }
            toast(response.data.message, {
                autoClose: 3000,
            });
        } catch (error) {
            //console.log(error);
        }
    };

    watchEffect(() => {
        // templateJsonData.value = [{ "type": "header", "parameters" : [] }, { "type": "body", "parameters" : [] }]; 
        if(typeof props.workflowDetail.last_data_mapped == 'object') props.workflowDetail.last_data_mapped = JSON.stringify(props.workflowDetail.last_data_mapped, undefined, 4);
    })

    onMounted(()=>{
        onLoadAndBroadcast();
        init();
    })

    defineExpose({
        onLoadAndBroadcast
    });

</script>
<template>
    <div class="bg-white md:bg-inherit rounded-[5px] text-[#000] h-auto md:overflow-y-auto border my-10 workflowDetils">
        <div class="border-b px-5 pt-5">
            <div>
                <h2 class="text-xl mb-1">{{ $t('Workflow Details') }}</h2>
                <p class="mb-6 flex items-center text-sm leading-6">
                    <span class="ml-1 mt-1">{{ $t('Send WhatsApp template message based on data received from 3rd party webhook call') }}</span>
                </p>
            </div>
        </div>
        <div class="p-5 workflowCallbackURL">
            <div class="flex justify-between pb-5">
                <h2 class="text-xl mb-1">{{ $t('Webhook Callback URL') }}</h2>
            </div>
            <div class="mb-6 flex justify-between webhookUrl">
                <p class="flex items-center text-sm leading-6">
                    <span class="ml-1 mt-1">{{ $t(props.workflowDetail.webhookUrl) }}</span>
                </p>
                <button @click="copyUrl(props.workflowDetail.webhookUrl)" class="rounded-md bg-primary px-3 py-2 text-sm text-white shadow-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 opacity-0">{{ $t(copyText) }}</button>
            </div>
            <div class="flex justify-end">
                <button @click="reCaptureWebhookResponse(props.workflowDetail.uuid)" class="rounded-md bg-primary px-3 py-2 text-sm text-white shadow-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600" :class="[{ 'opacity-50': recaptureForm }]" :disabled="recaptureForm">
                    <svg v-if="recaptureForm" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M12 2A10 10 0 1 0 22 12A10 10 0 0 0 12 2Zm0 18a8 8 0 1 1 8-8A8 8 0 0 1 12 20Z" opacity=".5"/><path fill="currentColor" d="M20 12h2A10 10 0 0 0 12 2V4A8 8 0 0 1 20 12Z"><animateTransform attributeName="transform" dur="1s" from="0 12 12" repeatCount="indefinite" to="360 12 12" type="rotate"/></path></svg>
                    <span v-else class="flex items-center gap-1">
                        <svg  xmlns="http://www.w3.org/2000/svg"  width="20"  height="20"  viewBox="0 0 24 24"  fill="currentColor"  class="icon icon-tabler icons-tabler-filled icon-tabler-circle-check"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336zm-1.293 5.953a1 1 0 0 0 -1.32 -.083l-.094 .083l-3.293 3.292l-1.293 -1.292l-.094 -.083a1 1 0 0 0 -1.403 1.403l.083 .094l2 2l.094 .083a1 1 0 0 0 1.226 0l.094 -.083l4 -4l.083 -.094a1 1 0 0 0 -.083 -1.32z" /></svg>
                        {{ $t('Re-Capture Webhook Response') }}
                    </span>
                </button>
            </div>
        </div>
    </div>
    <div class="flex gap-6">
        <div class="md:w-3/5 h-[fit-content] md:block hidden">
            <div class="bg-white border border-slate-200 h-[fit-content] md:block hidden mb-10">
                <div class="p-5">
                    <h4 class="mb-1 text-xl">{{ $t('Webhook Response Mapping') }}</h4>
                </div>
                <div class="flex-1 pb-10" v-if="props.workflowDetail.last_data_mapped && !recaptureForm">
                    <h4 class="mt-3 mx-4">{{ $t('Phone Number*') }}</h4>
                    <div class="flex items-center gap-4 p-5 pb-0">
                        <FormSelectCombo :templateDetails="selectData" :index="0" :type="$t('phone_number')" @templateDataForward="templateDataForward" :initData="workflowMessageInitData(templateJsonData,0,'phone_number')"/>
                    </div>
                    <h4 class="mt-3 mx-4" v-if="matchUrl(props.workflowDetail.metadata,'header').length">{{ $t('Header :') }}</h4>
                    <div v-for="(item, index) in matchUrl(props.workflowDetail.metadata,'header')" :key="index" class="flex items-center gap-4 p-5 pb-0">
                        <span>{{ $t('&#123;&#123;' + item.data + '&#125;&#125;') }}</span>
                        <FormSelectCombo :templateDetails="selectData" :index="index" :type="item.type" @templateDataForward="templateDataForward" :initData="workflowMessageInitData(templateJsonData,index,'header')"/>
                    </div>
                    <h4 class="mt-3 mx-4" v-if="matchUrl(props.workflowDetail.metadata,'body').length">{{ $t('Body :') }}</h4>
                    <div v-for="(item, index) in matchUrl(props.workflowDetail.metadata,'body')" :key="index" class="flex items-center gap-4 p-5 pb-0">
                        <span >{{ $t('&#123;&#123;' + item.data + '&#125;&#125;') }}</span>
                        <FormSelectCombo :templateDetails="selectData" :index="index" :type="item.type" @templateDataForward="templateDataForward" :initData="workflowMessageInitData(templateJsonData,index,'body')"/>
                    </div>
                    <h4 class="mt-3 mx-4" v-if="matchUrl(props.workflowDetail.metadata,'document').length">{{ $t('Doucment :') }}</h4>
                    <div v-for="(item, index) in matchUrl(props.workflowDetail.metadata,'document')" :key="index" class="flex items-center gap-4 p-5 pb-0">
                        <FormSelectCombo :templateDetails="selectData" :index="index" :type="item.type" @templateDataForward="templateDataForward" :initData="workflowMessageInitData(templateJsonData,index,'document')"/>
                    </div>
                    <h4 class="mt-3 mx-4" v-if="matchUrl(props.workflowDetail.metadata,'image').length">{{ $t('Image :') }}</h4>
                    <div v-for="(item, index) in matchUrl(props.workflowDetail.metadata,'image')" :key="index" class="flex items-center gap-4 p-5 pb-0">
                        <FormSelectCombo :templateDetails="selectData" :index="index" :type="item.type" @templateDataForward="templateDataForward" :initData="workflowMessageInitData(templateJsonData,index,'image')"/>
                    </div>
                </div>
                <div class="flex flex-1 items-center justify-center pb-10" v-if="recaptureForm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M12 2A10 10 0 1 0 22 12A10 10 0 0 0 12 2Zm0 18a8 8 0 1 1 8-8A8 8 0 0 1 12 20Z" opacity=".5"/><path fill="currentColor" d="M20 12h2A10 10 0 0 0 12 2V4A8 8 0 0 1 20 12Z"><animateTransform attributeName="transform" dur="1s" from="0 12 12" repeatCount="indefinite" to="360 12 12" type="rotate"/></path></svg>
                </div>
            </div>
            <div class="bg-white border border-slate-200 h-[fit-content] md:block hidden mb-10">
                <div class="p-5">
                    <h4 class="mb-1 text-xl">{{ $t('Actions following successful webhook call') }}</h4>
                </div>
                <div class="flex-1 " v-if="props.workflowDetail.last_data_mapped && !recaptureForm">
                    <h4 class="mt-3 mx-4">{{ $t('Subscriber Name') }}</h4>
                    <div class="flex items-center gap-4 p-5 pb-10">
                        <FormSelectCombo :templateDetails="selectData" :index="0" :type="$t('subscriber_name')" @templateDataForward="templateDataForward" :initData="subscriber_name"/>
                    </div>
                </div>
                <div class="flex flex-1 items-center justify-center pb-10" v-if="recaptureForm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M12 2A10 10 0 1 0 22 12A10 10 0 0 0 12 2Zm0 18a8 8 0 1 1 8-8A8 8 0 0 1 12 20Z" opacity=".5"/><path fill="currentColor" d="M20 12h2A10 10 0 0 0 12 2V4A8 8 0 0 1 20 12Z"><animateTransform attributeName="transform" dur="1s" from="0 12 12" repeatCount="indefinite" to="360 12 12" type="rotate"/></path></svg>
                </div>
            </div>
        </div>
        <div class="md:w-2/5 h-[fit-content] md:block hidden">
            <div class="bg-white border border-slate-200 p-5">
                <h2 class="text-xl mb-1">{{ $t('Data Formatters') }}</h2>
                <p class="mb-6 flex items-center text-sm leading-6">
                    <span class="ml-1 mt-1">{{ $t('Format or manipulate webhook data') }}</span>
                </p>
                <div class="flex justify-between mb-4">
                    <div class="md:bg-white flex items-center border border-primary md:border-none md:shadow-sm h-12 md:h-10 w-full md:w-80 rounded-[0.5rem] text-xl md:text-sm">
                        <span class="pl-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 15l6 6m-11-4a7 7 0 1 1 0-14a7 7 0 0 1 0 14Z"/></svg>
                        </span>
                        <input @input="search" v-model="params.search" type="text" class="outline-none px-4 w-full" :placeholder="$t('Search')">
                        <button v-if="isSearching === false && params.search" @click="clearSearch" type="button" class="pr-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10s10-4.5 10-10S17.5 2 12 2zm3.7 12.3c.4.4.4 1 0 1.4c-.4.4-1 .4-1.4 0L12 13.4l-2.3 2.3c-.4.4-1 .4-1.4 0c-.4-.4-.4-1 0-1.4l2.3-2.3l-2.3-2.3c-.4-.4-.4-1 0-1.4c.4-.4 1-.4 1.4 0l2.3 2.3l2.3-2.3c.4-.4 1-.4 1.4 0c.4.4.4 1 0 1.4L13.4 12l2.3 2.3z"/></svg>
                        </button>
                        <span v-if="isSearching" class="pr-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><circle cx="12" cy="3.5" r="1.5" fill="currentColor" opacity="0"><animateTransform attributeName="transform" calcMode="discrete" dur="2.4s" repeatCount="indefinite" type="rotate" values="0 12 12;90 12 12;180 12 12;270 12 12"/><animate attributeName="opacity" dur="0.6s" keyTimes="0;0.5;1" repeatCount="indefinite" values="1;1;0"/></circle><circle cx="12" cy="3.5" r="1.5" fill="currentColor" opacity="0"><animateTransform attributeName="transform" begin="0.2s" calcMode="discrete" dur="2.4s" repeatCount="indefinite" type="rotate" values="30 12 12;120 12 12;210 12 12;300 12 12"/><animate attributeName="opacity" begin="0.2s" dur="0.6s" keyTimes="0;0.5;1" repeatCount="indefinite" values="1;1;0"/></circle><circle cx="12" cy="3.5" r="1.5" fill="currentColor" opacity="0"><animateTransform attributeName="transform" begin="0.4s" calcMode="discrete" dur="2.4s" repeatCount="indefinite" type="rotate" values="60 12 12;150 12 12;240 12 12;330 12 12"/><animate attributeName="opacity" begin="0.4s" dur="0.6s" keyTimes="0;0.5;1" repeatCount="indefinite" values="1;1;0"/></circle></svg>
                        </span>
                    </div>
                    <button  @click="isOpenFormModal = true;" class="rounded-md bg-primary px-3 py-2 text-sm text-white shadow-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 flex items-center justify-center gap-1">
                        <svg  xmlns="http://www.w3.org/2000/svg"  width="20"  height="20"  viewBox="0 0 24 24"  fill="currentColor"  class="icon icon-tabler icons-tabler-filled icon-tabler-circle-plus"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4.929 4.929a10 10 0 1 1 14.141 14.141a10 10 0 0 1 -14.14 -14.14zm8.071 4.071a1 1 0 1 0 -2 0v2h-2a1 1 0 1 0 0 2h2v2a1 1 0 1 0 2 0v-2h2a1 1 0 1 0 0 -2h-2v-2z" /></svg>
                        {{ $t('New') }}
                    </button>
                </div>
                <div>
                    <Table :rows="dataFormatterRows" :isPaginationDisable="true">
                        <TableHeader>
                            <TableHeaderRow>
                                <TableHeaderRowItem :position="'first'" :class="'font-bold'">{{ $t('Formatter Definition') }}</TableHeaderRowItem>
                                <TableHeaderRowItem>{{ $t('Actions') }}</TableHeaderRowItem>
                            </TableHeaderRow>
                        </TableHeader>
                        <TableBody>
                            <TableBodyRow v-for="(item, index) in dataFormatterRows" :key="index" >
                                <TableBodyRowItem :position="'first'" class="capitalize">{{ item?.formatter_definition }}</TableBodyRowItem>
                                <TableBodyRowItem class="hidden sm:table-cell">
                                    <span @click="openAlert(item?.uuid)" class="block cursor-pointer w-fit">
                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="20"  height="20"  viewBox="0 0 24 24"  fill="#b91c1c"  class="icon icon-tabler icons-tabler-filled icon-tabler-trash"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 6a1 1 0 0 1 .117 1.993l-.117 .007h-.081l-.919 11a3 3 0 0 1 -2.824 2.995l-.176 .005h-8c-1.598 0 -2.904 -1.249 -2.992 -2.75l-.005 -.167l-.923 -11.083h-.08a1 1 0 0 1 -.117 -1.993l.117 -.007h16z" /><path d="M14 2a2 2 0 0 1 2 2a1 1 0 0 1 -1.993 .117l-.007 -.117h-4l-.007 .117a1 1 0 0 1 -1.993 -.117a2 2 0 0 1 1.85 -1.995l.15 -.005h4z" /></svg>
                                    </span>
                                </TableBodyRowItem>
                            </TableBodyRow>
                        </TableBody>
                    </Table>
                </div>
            </div>
            <div class="bg-white border border-slate-200 p-5">
                <h2 class="text-xl mb-1">{{ $t('Raw Data') }}</h2>
                <p class="mb-6 flex items-center text-sm leading-6">
                    <span class="ml-1 mt-1">{{ $t('The original webhook data received in raw format') }}</span>
                </p>
                <Disclosure v-slot="{ open }">
                    <DisclosureButton class="py-2">
                        <button @click="loadRawData(props.workflowDetail.uuid)" class="rounded-md bg-primary px-3 py-2 text-sm text-white shadow-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">{{ $t('Load Data') }}</button>
                    </DisclosureButton>
                    <transition enter="duration-300 ease-out">
                        <DisclosurePanel class="text-gray-500">
                            <FormTextArea v-model="props.workflowDetail.last_data_mapped" :textAreaRows="12" />
                        </DisclosurePanel>
                    </transition>
                </Disclosure>
            </div>
        </div>
    </div>
    <div class="flex-1 py-5 " v-if="props.workflowDetail.last_data_mapped">
        <button @click="saveWorkFlow(props.workflowDetail.uuid)" class="rounded-md bg-primary px-3 py-2 text-sm text-white shadow-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"  :class="[{ 'opacity-50': saveWorkFlowForm }]" :disabled="saveWorkFlowForm">
            <svg v-if="saveWorkFlowForm" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M12 2A10 10 0 1 0 22 12A10 10 0 0 0 12 2Zm0 18a8 8 0 1 1 8-8A8 8 0 0 1 12 20Z" opacity=".5"/><path fill="currentColor" d="M20 12h2A10 10 0 0 0 12 2V4A8 8 0 0 1 20 12Z"><animateTransform attributeName="transform" dur="1s" from="0 12 12" repeatCount="indefinite" to="360 12 12" type="rotate"/></path></svg>
            <span v-else class="flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-save"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
            {{ $t('Save Workflow') }}</span>
        </button>
    </div>
    <Modal :label="$t('Data Formatter')" :isOpen=isOpenFormModal :extendMaxWidth="true" :closeBtn="true" @close="isOpenFormModal = false" :customStyle="true" >
        <div class="grid grid-cols-1 gap-x-6 gap-y-4">
            <form @submit.prevent="submitForm()">
                <div class="px-8 py-4">
                    <div class="grid gap-x-6 gap-y-4 modalFormGrid mb-6">
                        <FormInput v-model="formatterForm.name" :error="formatterForm.errors.name" :name="$t('Name *')" :placeholder="'Name it to identify it later'" :type="'text'" :class="'sm:col-span-6'"/>
                        <div :class="'sm:col-span-6'">
                            <label for="name" class="block text-sm leading-6 text-gray-900">{{ $t('Action *') }}</label>
                            <DataFormatterComboBox @dataFormatter="dataFormatter"></DataFormatterComboBox>
                        </div>
                    </div>
                    <div class="grid gap-x-6 gap-y-4 modalFormGrid mb-6" v-if="formatterForm.action && (formatterForm.action == 'concat_left' || formatterForm.action == 'concat_right' || formatterForm.action == 'concat_list_items' || formatterForm.action == 'split')">
                        <FormInput v-model="formatterForm.glue" :error="formatterForm.errors.glue" :name="$t('Glue')" :placeholder="'string'" :type="'text'" :class="'sm:col-span-6'" v-if="formatterForm.action == 'concat_left' || formatterForm.action == 'concat_right' || formatterForm.action == 'concat_list_items'"/>
                        <FormInput v-model="formatterForm.separator" :error="formatterForm.errors.separator" :name="$t('Separator *')" :placeholder="'string'" :type="'text'" :class="'sm:col-span-6'" v-else/>
                        <FormInput v-model="formatterForm.concat" :error="formatterForm.errors.concat" :name="$t('Concat *')" :placeholder="'string'" :type="'text'" :class="'sm:col-span-6'" v-if="formatterForm.action == 'concat_left' || formatterForm.action == 'concat_right'"/>
                        <FormInput v-model="formatterForm.position" :error="formatterForm.errors.position" :name="$t('Position *')" :placeholder="'string'" :type="'text'" :class="'sm:col-span-6'" v-else/>
                    </div>
                    <div class="grid gap-x-6 gap-y-4 singleGrid mb-6" v-if="formatterForm.action && (formatterForm.action == 'day_add' || formatterForm.action == 'day_subtract')">
                        <FormInput v-model="formatterForm.day" :error="formatterForm.errors.day" :name="$t('Day *')" :placeholder="'integer'" :type="'text'" :class="'sm:col-span-6'"/>
                    </div>
                    <div class="grid gap-x-6 gap-y-4 singleGrid mb-6" v-if="formatterForm.action && formatterForm.action == 'default_value'">
                        <FormInput v-model="formatterForm.default" :error="formatterForm.errors.default" :name="$t('Default *')" :placeholder="'string'" :type="'text'" :class="'sm:col-span-6'"/>
                    </div>
                    <div class="grid gap-x-6 gap-y-4 modalFormGrid mb-6" v-if="formatterForm.action && formatterForm.action == 'format_number'">
                        <FormInput v-model="formatterForm.decimal" :error="formatterForm.errors.decimal" :name="$t('Decimal *')" :placeholder="'integer'" :type="'text'" :class="'sm:col-span-4'"/>
                        <FormInput v-model="formatterForm.decimal_separator" :error="formatterForm.errors.decimal_separator" :name="$t('Decimal Separator')" :placeholder="'string'" :type="'text'" :class="'sm:col-span-4'"/>
                        <FormInput v-model="formatterForm.thousand_separator" :error="formatterForm.errors.thousand_separator" :name="$t('Thousand Separator')" :placeholder="'string'" :type="'text'" :class="'sm:col-span-4'"/>
                    </div>
                    <div class="grid gap-x-6 gap-y-4 singleGrid mb-6" v-if="formatterForm.action && (formatterForm.action == 'number_add' || formatterForm.action == 'number_multiply' || formatterForm.action == 'number_subtract')">
                        <FormInput v-model="formatterForm.number" :error="formatterForm.errors.number" :name="$t('Number *')" :placeholder="'integer'" :type="'text'" :class="'sm:col-span-6'"/>
                    </div>
                    <div class="grid gap-x-6 gap-y-4 modalFormGrid mb-6" v-if="formatterForm.action && formatterForm.action == 'replace'">
                        <FormInput v-model="formatterForm.search" :error="formatterForm.errors.search" :name="$t('Search *')" :placeholder="'string'" :type="'text'" :class="'sm:col-span-6'"/>
                        <FormInput v-model="formatterForm.replace" :error="formatterForm.errors.replace" :name="$t('Replace *')" :placeholder="'string'" :type="'text'" :class="'sm:col-span-6'"/>
                    </div>
                    <div class="grid gap-x-6 gap-y-4 singleGrid mb-6" v-if="formatterForm.action && formatterForm.action == 'shorten'">
                        <FormInput v-model="formatterForm.shorten" :error="formatterForm.errors.shorten" :name="$t('Limit *')" :placeholder="'integer'" :type="'text'" :class="'sm:col-span-6'"/>
                    </div>
                    <div class="grid gap-x-6 gap-y-4 singleGrid mb-6" v-if="formatterForm.action && formatterForm.action == 'static_value'">
                        <FormInput v-model="formatterForm.value" :error="formatterForm.errors.value" :name="$t('Value *')" :placeholder="'string'" :type="'text'" :class="'sm:col-span-6'"/>
                    </div>
                    <div class="grid gap-x-6 gap-y-4 singleGrid mb-6" v-if="formatterForm.action && (formatterForm.action == 'trim_left' || formatterForm.action == 'trim_right')">
                        <FormInput v-model="formatterForm.trim" :error="formatterForm.errors.trim" :name="$t('Trim *')" :placeholder="'string'" :type="'text'" :class="'sm:col-span-6'"/>
                    </div>
                    <div class="alert-light-warning bor border border-dashed border-warning flex gap-3 items-center p-5 relative mt-5" style="border-radius: 4px;" v-if="closeInfoWarning && formatterForm.action">
                        <div>
                            <svg  xmlns="http://www.w3.org/2000/svg"  width="42"  height="42"  viewBox="0 0 24 24"  fill="#fdac41"  class="icon icon-tabler icons-tabler-filled icon-tabler-info-circle"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 2c5.523 0 10 4.477 10 10a10 10 0 0 1 -19.995 .324l-.005 -.324l.004 -.28c.148 -5.393 4.566 -9.72 9.996 -9.72zm0 9h-1l-.117 .007a1 1 0 0 0 0 1.986l.117 .007v3l.007 .117a1 1 0 0 0 .876 .876l.117 .007h1l.117 -.007a1 1 0 0 0 .876 -.876l.007 -.117l-.007 -.117a1 1 0 0 0 -.764 -.857l-.112 -.02l-.117 -.006v-3l-.007 -.117a1 1 0 0 0 -.876 -.876l-.117 -.007zm.01 -3l-.127 .007a1 1 0 0 0 0 1.986l.117 .007l.127 -.007a1 1 0 0 0 0 -1.986l-.117 -.007z" /></svg>
                        </div>
                        <div>
                            <h5 class="mb-2 text-[18px] text-warning">
                                Use Case
                            </h5>
                            <p class="text-[14px]">
                                {{ useCaseContent?.main_content }}
                                <br/>
                                <i>{{ useCaseContent?.italic_content }}</i>
                            </p>
                        </div>
                        <button type="button" @click="closeInfoWarning=false" class="absolute top-0 right-0 py-2 px-2.5"><svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-x"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12" /><path d="M6 6l12 12" /></svg></button>
                    </div>
                    <div class="alert-light-info bor border border-dashed border-info flex gap-3 items-center p-5 relative mt-5" style="border-radius: 4px;" v-if="closeWhiteSpace">
                        <div>
                            <svg  xmlns="http://www.w3.org/2000/svg"  width="42"  height="42"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-keyboard"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M2 6m0 2a2 2 0 0 1 2 -2h16a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-16a2 2 0 0 1 -2 -2z" /><path d="M6 10l0 .01" /><path d="M10 10l0 .01" /><path d="M14 10l0 .01" /><path d="M18 10l0 .01" /><path d="M6 14l0 .01" /><path d="M18 14l0 .01" /><path d="M10 14l4 .01" /></svg>
                        </div>
                        <div>
                            <h5 class="mb-2 text-[18px]">
                                How to use `Whitespace` ?
                            </h5>
                            <p class="text-[14px]">
                                You can use whitespace as parameter value. Just type [space] as parameter value.
                            </p>
                        </div>
                        <button type="button" @click="closeWhiteSpace=false" class="absolute top-0 right-0 py-2 px-2.5"><svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-x"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12" /><path d="M6 6l12 12" /></svg></button>
                    </div>
                </div>
                <div class="border-t flex mt-4 px-8 py-4">
                    <button :class="['inline-flex justify-center rounded-md border border-transparent bg-primary px-4 py-2 text-sm text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2', { 'opacity-50': formatterForm.processing }]" :disabled="formatterForm.processing">
                        <svg v-if="formatterForm.processing" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M12 2A10 10 0 1 0 22 12A10 10 0 0 0 12 2Zm0 18a8 8 0 1 1 8-8A8 8 0 0 1 12 20Z" opacity=".5"/><path fill="currentColor" d="M20 12h2A10 10 0 0 0 12 2V4A8 8 0 0 1 20 12Z"><animateTransform attributeName="transform" dur="1s" from="0 12 12" repeatCount="indefinite" to="360 12 12" type="rotate"/></path></svg>
                        <span v-else class="flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-save"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>{{ $t('Save Formatter') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </Modal>
    <!-- Alert Modal Component-->
    <AlertModal 
        v-model="isOpenAlert" 
        @confirm="() => confirmAlert(deleteDataFormatterAction)"
        :label = "$t('Delete row')" 
        :description = "$t('Are you sure you want to delete this row? This action can not be undone')"
    />
</template>