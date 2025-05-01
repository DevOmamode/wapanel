<template>
    <Table :rows="rows" :isPaginationDisable="true" :className="'max-h-60 overflow-auto pb-5 px-8'" :url="true">
        <TableHeader>
            <TableHeaderRow>
                <TableHeaderRowItem :position="'first'">{{ $t('S.No') }}</TableHeaderRowItem>
                <!-- <TableHeaderRowItem >{{ $t('Status') }}</TableHeaderRowItem> -->
                <TableHeaderRowItem>{{ $t('Image') }}</TableHeaderRowItem>
                <TableHeaderRowItem>{{ $t('Name') }}</TableHeaderRowItem>
                <TableHeaderRowItem>{{ $t('Quantity') }}</TableHeaderRowItem>
                <TableHeaderRowItem>{{ $t('MRP') }}</TableHeaderRowItem>
                <TableHeaderRowItem>{{ $t('Price') }}</TableHeaderRowItem>
                <TableHeaderRowItem>{{ $t('Retailer ID') }}</TableHeaderRowItem>
                <TableHeaderRowItem>{{ $t('Product Id') }}</TableHeaderRowItem>
                <!-- <TableHeaderRowItem>{{ $t('Price') }}</TableHeaderRowItem> -->
                <!-- <TableHeaderRowItem>{{ $t('Synced at') }}</TableHeaderRowItem> -->
            </TableHeaderRow>
        </TableHeader>
        <TableBody>
            <TableBodyRow v-for="(item, index) in rows.data" :key="index">
                <TableBodyRowItem :position="'first'" class="capitalize">{{ index + 1 }}</TableBodyRowItem>
                <!-- <TableBodyRo wItem class="capitalize">{{ item.status }}</TableBodyRowItem> -->
                <TableBodyRowItem class="hidden sm:table-cell">
                    <img v-if="item.image" :src="item.image" alt="" class="w-20 h-20 rounded-full dark:bg-gray-500">
                </TableBodyRowItem>
                <TableBodyRowItem class="hidden sm:table-cell">{{ item.name }}</TableBodyRowItem>
                <TableBodyRowItem class="hidden sm:table-cell">{{ item.quantity }}</TableBodyRowItem>
                <TableBodyRowItem class="hidden sm:table-cell">{{ item.price }}</TableBodyRowItem>
                <TableBodyRowItem class="hidden sm:table-cell">{{ item.total }}</TableBodyRowItem>
                <TableBodyRowItem class="hidden sm:table-cell">{{ item.retailer_id }}</TableBodyRowItem>
                <TableBodyRowItem class="hidden sm:table-cell">{{ item.product_id }}</TableBodyRowItem>
                <!-- <TableBodyRowItem class="hidden sm:table-cell">{{ item.assign_label }}</TableBodyRowItem> -->
                <!-- <TableBodyRowItem class="hidden sm:table-cell">{{ item.synced_at }}</TableBodyRowItem> -->
            </TableBodyRow>
        </TableBody>
    </Table>
</template>

<script setup>
import axios from 'axios';
import { ref, computed } from 'vue';
import debounce from 'lodash/debounce';
import { router, useForm, usePage } from "@inertiajs/vue3";
import AlertModal from '@/Components/AlertModal.vue';
import Table from '@/Components/Table.vue';
import TableHeader from '@/Components/TableHeader.vue';
import TableHeaderRow from '@/Components/TableHeaderRow.vue';
import TableHeaderRowItem from '@/Components/TableHeaderRowItem.vue';
import TableBody from '@/Components/TableBody.vue';
import TableBodyRow from '@/Components/TableBodyRow.vue';
import TableBodyRowItem from '@/Components/TableBodyRowItem.vue';
import { toast } from 'vue3-toastify';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownItemGroup from '@/Components/DropdownItemGroup.vue';
import DropdownItem from '@/Components/DropdownItem.vue';
import ProductsTable from '@/Components/Tables/ProductsTable.vue';
const props = defineProps(['rows']);

</script>