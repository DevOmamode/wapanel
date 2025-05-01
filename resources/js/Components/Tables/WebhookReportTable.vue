<script setup>
    import axios from "axios";
    import { ref, computed } from 'vue';
    import debounce from 'lodash/debounce';
    import { router, usePage } from "@inertiajs/vue3";
    import Table from '@/Components/Table.vue';
    import TableHeader from '@/Components/TableHeader.vue';
    import TableHeaderRow from '@/Components/TableHeaderRow.vue';
    import TableHeaderRowItem from '@/Components/TableHeaderRowItem.vue';
    import TableBody from '@/Components/TableBody.vue';
    import TableBodyRow from '@/Components/TableBodyRow.vue';
    import TableBodyRowItem from '@/Components/TableBodyRowItem.vue';
    import 'vue3-toastify/dist/index.css';
    import FormTextArea from '@/Components/FormTextArea.vue';
    const props = defineProps({
        rows: {
            type: Object,
            required: true,
        },
        filters: {
            type: Object
        }
    });
</script>
<template>
    <Table :rows="rows">
        <TableHeader>
            <TableHeaderRow>
                <TableHeaderRowItem :position="'first'">{{ $t('S.No') }}</TableHeaderRowItem>
                <TableHeaderRowItem>{{ $t('Workflow') }}</TableHeaderRowItem>
                <TableHeaderRowItem>{{ $t('Phone Number') }}</TableHeaderRowItem>
                <TableHeaderRowItem>{{ $t('Status') }}</TableHeaderRowItem>
                <TableHeaderRowItem>{{ $t('Response') }}</TableHeaderRowItem>
                <!-- <TableHeaderRowItem>{{ $t('Triggered at') }}</TableHeaderRowItem> -->
                <TableHeaderRowItem>{{ $t('Scheduled at') }}</TableHeaderRowItem>
                <TableHeaderRowItem>{{ $t('Metadata') }}</TableHeaderRowItem>
                <TableHeaderRowItem>{{ $t('Webhook Data') }}</TableHeaderRowItem>
                <!-- <TableHeaderRowItem v-if="user.teams[0].role === 'owner'" :position="'last'"></TableHeaderRowItem> -->
            </TableHeaderRow>
        </TableHeader>
        <TableBody>
            <TableBodyRow v-for="(item, index) in rows.data" :key="index" >
                <TableBodyRowItem :position="'first'" class="capitalize">{{ index + 1 }}</TableBodyRowItem>
                <TableBodyRowItem class="hidden sm:table-cell">{{ item.name }}</TableBodyRowItem>
                <TableBodyRowItem class="hidden sm:table-cell">{{ item.phone_number }}</TableBodyRowItem>
                <TableBodyRowItem class="capitalize hidden sm:table-cell" >
                    <span class="flex fill-current gap-1" :class="{ 'text-green-600': item.status == 'success', 'text-red-600': item.status == 'failed' }">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" class="icon icon-tabler icons-tabler-filled icon-tabler-circle">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M7 3.34a10 10 0 1 1 -4.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 4.995 -8.336z" />
                        </svg>
                        {{ item.status }}
                    </span>
                </TableBodyRowItem>
                <TableBodyRowItem class="hidden sm:table-cell">
                    <FormTextArea v-if="item.response" v-model="item.response" :textAreaRows="1"/>
                    <span v-if="!item.response" class="flex justify-center">-</span>
                </TableBodyRowItem>
                <!-- <TableBodyRowItem class="hidden text-blue-600 sm:table-cell">{{ item.triggered_at }}</TableBodyRowItem> -->
                <TableBodyRowItem class="hidden sm:table-cell">{{ item.created_at }}</TableBodyRowItem>
                <TableBodyRowItem class="hidden sm:table-cell">
                    <FormTextArea v-if="item.template" v-model="item.template" :textAreaRows="1"/>
                    <span v-if="!item.template" class="flex justify-center">-</span>
                </TableBodyRowItem>
                <TableBodyRowItem>
                    <FormTextArea v-if="item.metadata" v-model="item.metadata" :textAreaRows="1"/>
                    <span v-if="!item.metadata" class="flex justify-center">-</span>
                </TableBodyRowItem>
            </TableBodyRow>
        </TableBody>
    </Table>
</template>