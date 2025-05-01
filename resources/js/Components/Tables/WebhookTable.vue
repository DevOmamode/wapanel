<script setup>
    import axios from "axios";
    import { ref, computed } from 'vue';
    import debounce from 'lodash/debounce';
    import { router, usePage } from "@inertiajs/vue3";
    import AlertModal from '@/Components/AlertModal.vue';
    import { useForm } from "@inertiajs/vue3";
    import { useAlertModal } from '@/Composables/useAlertModal';
    import Table from '@/Components/Table.vue';
    import TableHeader from '@/Components/TableHeader.vue';
    import TableHeaderRow from '@/Components/TableHeaderRow.vue';
    import TableHeaderRowItem from '@/Components/TableHeaderRowItem.vue';
    import TableBody from '@/Components/TableBody.vue';
    import TableBodyRow from '@/Components/TableBodyRow.vue';
    import TableBodyRowItem from '@/Components/TableBodyRowItem.vue';
    import Dropdown from '@/Components/Dropdown.vue';
    import DropdownItemGroup from '@/Components/DropdownItemGroup.vue';
    import DropdownItem from '@/Components/DropdownItem.vue';
    import FormToggleSwitch from '@/Components/FormToggleSwitch.vue';
    import { toast } from 'vue3-toastify';
    import 'vue3-toastify/dist/index.css';
    const props = defineProps({
        rows: {
            type: Object,
            required: true,
        },
        filters: {
            type: Object
        }
    });

    const { isOpenAlert, openAlert, confirmAlert } = useAlertModal();

    const emit = defineEmits(['edit','copyUrl','delete','openModal']);

    const deleteAction = async (uuid) => {
        try {
            const response = await axios.delete(`/whatsapp/workflow/${uuid}`);

            if (response.status === 200 && response.data.success) {
                const idx = props.rows.data.findIndex((i) => i.uuid === uuid);
                props.rows.data.splice(idx, 1);
            }
            toast(response.data.message, {
                autoClose: 3000,
            });
            emit('delete', true);
        } catch (error) {
            //console.log(error);
        }
    };

    function edit(data) {
        emit('edit', { data });
    }

    const copyUrl = async (url) => {
        emit('copyUrl', url);
        toast('Webhook URL Copied!', {
            autoClose: 3000,
        });
    };
    const statusUpdate = async (uuid,status) => {
        const formData = new FormData();
        formData.append('uuid', uuid);
        formData.append('status', status);
        const response = await axios.post('/status/update',formData);
    };

    const verified = (verified_data) => {
        var inx = props.rows.data.findIndex(e=>{return e.uuid == verified_data.uuid});
        if(inx != -1) props.rows.data[inx] = verified_data.workflowDetail;
    }

    const openModal = () => {
        emit('openModal');
    }

    defineExpose({
        verified
    });
</script>
<template>
    <Table :rows="rows">
        <TableHeader>
            <TableHeaderRow>
                <TableHeaderRowItem :position="'first'">{{ $t('Workflow') }}</TableHeaderRowItem>
                <TableHeaderRowItem>{{ $t('Template') }}</TableHeaderRowItem>
                <TableHeaderRowItem>{{ $t('Status') }}</TableHeaderRowItem>
                <TableHeaderRowItem>{{ $t('Verified') }}</TableHeaderRowItem>
                <TableHeaderRowItem>{{ $t('Targeted') }}</TableHeaderRowItem>
                <TableHeaderRowItem>{{ $t('Delivered') }}</TableHeaderRowItem>
                <TableHeaderRowItem>{{ $t('Failed') }}</TableHeaderRowItem>
                <TableHeaderRowItem>{{ $t('Actions') }}</TableHeaderRowItem>
                <!-- <TableHeaderRowItem v-if="user.teams[0].role === 'owner'" :position="'last'"></TableHeaderRowItem> -->
            </TableHeaderRow>
        </TableHeader>
        <TableBody>
            <TableBodyRow v-for="(item, index) in rows.data" :key="index" >
                <TableBodyRowItem :position="'first'" class="capitalize">{{ item.name }}</TableBodyRowItem>
                <TableBodyRowItem class="hidden sm:table-cell">{{ item.template_name }}</TableBodyRowItem>
                <TableBodyRowItem class="hidden sm:table-cell">
                    <FormToggleSwitch @click="statusUpdate(item.uuid,item.status)" v-model="item.status"/>
                </TableBodyRowItem>
                <TableBodyRowItem class="capitalize hidden sm:table-cell">
                    <span v-if="item.verified == 1" class="flex gap-1">
                        <svg  xmlns="http://www.w3.org/2000/svg"  width="20"  height="20"  viewBox="0 0 24 24"  fill="#034737"  class="icon icon-tabler icons-tabler-filled icon-tabler-circle-check"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336zm-1.293 5.953a1 1 0 0 0 -1.32 -.083l-.094 .083l-3.293 3.292l-1.293 -1.292l-.094 -.083a1 1 0 0 0 -1.403 1.403l.083 .094l2 2l.094 .083a1 1 0 0 0 1.226 0l.094 -.083l4 -4l.083 -.094a1 1 0 0 0 -.083 -1.32z" /></svg>
                        {{ $t('Yes') }}
                    </span>
                    <span v-else class="flex gap-1">
                        <svg  xmlns="http://www.w3.org/2000/svg"  width="20"  height="20"  viewBox="0 0 24 24"  fill="#6c757d"  class="icon icon-tabler icons-tabler-filled icon-tabler-circle-x"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336zm-6.489 5.8a1 1 0 0 0 -1.218 1.567l1.292 1.293l-1.292 1.293l-.083 .094a1 1 0 0 0 1.497 1.32l1.293 -1.292l1.293 1.292l.094 .083a1 1 0 0 0 1.32 -1.497l-1.292 -1.293l1.292 -1.293l.083 -.094a1 1 0 0 0 -1.497 -1.32l-1.293 1.292l-1.293 -1.292l-.094 -.083z" /></svg>
                        {{ $t('No') }}
                    </span>
                </TableBodyRowItem>
                <TableBodyRowItem class="hidden text-blue-600 sm:table-cell">{{ item.total_call }}</TableBodyRowItem>
                <TableBodyRowItem class="hidden text-green-600 sm:table-cell">{{ item.total_success }}</TableBodyRowItem>
                <TableBodyRowItem class="hidden text-red-600 sm:table-cell">{{ item.total_error }}</TableBodyRowItem>
                <TableBodyRowItem>
                    <Dropdown :align="'right'" class="mt-2">
                        <button class="inline-flex w-full justify-center rounded-md text-sm font-medium text-black hover:bg-opacity-30 focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75">
                            <span class="hover:bg-[#F6F7F9] hover:rounded-full w-[fit-content] p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                    <path fill="currentColor" d="M12 16a2 2 0 0 1 2 2a2 2 0 0 1-2 2a2 2 0 0 1-2-2a2 2 0 0 1 2-2m0-6a2 2 0 0 1 2 2a2 2 0 0 1-2 2a2 2 0 0 1-2-2a2 2 0 0 1 2-2m0-6a2 2 0 0 1 2 2a2 2 0 0 1-2 2a2 2 0 0 1-2-2a2 2 0 0 1 2-2Z"/>
                                </svg>
                            </span>
                        </button>
                        <template #items>
                            <DropdownItemGroup>
                                <DropdownItem as="button" @click="copyUrl(item.webhookUrl)">{{ $t('Copy URL') }}</DropdownItem>
                                <DropdownItem as="button" @click="edit(item)">{{ $t('Edit') }}</DropdownItem>
                                <DropdownItem as="a" v-bind:href="'/whatsapp/workflow/' + item.uuid">{{ $t('Report') }}</DropdownItem>
                                <DropdownItem as="button" @click="openAlert(item.uuid)">{{ $t('Delete') }}</DropdownItem>
                            </DropdownItemGroup>
                        </template>
                    </Dropdown>
                </TableBodyRowItem>
            </TableBodyRow>
        </TableBody>
    </Table>
    <div v-if="rows.data.length == 0" class="bg-white rounded-xl">
        <div class="p-4 py-8">
            <div class="flex justify-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg"  width="72"  height="72"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-webhook"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4.876 13.61a4 4 0 1 0 6.124 3.39h6" /><path d="M15.066 20.502a4 4 0 1 0 1.934 -7.502c-.706 0 -1.424 .179 -2 .5l-3 -5.5" /><path d="M16 8a4 4 0 1 0 -8 0c0 1.506 .77 2.818 2 3.5l-3 5.5" /></svg>
            </div>
            <h3 class="text-center text-lg font-medium mb-4">{{ $t('You don\'t have any Workflow') }}</h3>
            <div class="flex justify-center">
                <button @click="openModal()" class="rounded-md bg-primary px-3 py-2 text-sm text-white shadow-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">{{ $t('Create') }}</button>
            </div>
        </div>
    </div>
    <!-- Alert Modal Component-->
    <AlertModal 
        v-model="isOpenAlert" 
        @confirm="() => confirmAlert(deleteAction)"
        :label = "$t('Delete row')" 
        :description = "$t('Are you sure you want to delete this row? This action can not be undone')"
    />
</template>