<script setup>
    import { ref, computed } from 'vue';
    import debounce from 'lodash/debounce';
    import { router, useForm, usePage } from "@inertiajs/vue3";
    import Table from '@/Components/Table.vue';
    import TableHeader from '@/Components/TableHeader.vue';
    import TableHeaderRow from '@/Components/TableHeaderRow.vue';
    import TableHeaderRowItem from '@/Components/TableHeaderRowItem.vue';
    import TableBody from '@/Components/TableBody.vue';
    import TableBodyRow from '@/Components/TableBodyRow.vue';
    import TableBodyRowItem from '@/Components/TableBodyRowItem.vue';
    import { toast } from 'vue3-toastify';
    const props = defineProps(['rows','filters']);    
    const isSearching = ref(false);
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
    });
    const formUrl = ref('/sync_products');
    const syncProducts = async () => {
        try {
            syncProgress.value = true;
            form.post(formUrl.value, {
                onFinish: (data) => {
                    syncProgress.value = false;
                }
            });
        } catch (error) {
            console.log(error);
        }
    }
    
</script>
<template>
    <div class="flex mb-6 mt-6 items-center" :class="[rows.data.length ? 'justify-between' : 'justify-end']">
        <div class="md:bg-white flex items-center border border-primary md:border-none md:shadow-sm h-12 md:h-10 w-full md:w-80 rounded-[0.5rem] text-xl md:text-sm" v-if="rows.data.length">
            <span class="pl-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 15l6 6m-11-4a7 7 0 1 1 0-14a7 7 0 0 1 0 14Z"/></svg>
            </span>
            <input @input="search" v-model="params.search" type="text" class="outline-none px-4 w-full" :placeholder="$t('Search Product')">
            <button v-if="isSearching === false && params.search" @click="clearSearch" type="button" class="pr-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10s10-4.5 10-10S17.5 2 12 2zm3.7 12.3c.4.4.4 1 0 1.4c-.4.4-1 .4-1.4 0L12 13.4l-2.3 2.3c-.4.4-1 .4-1.4 0c-.4-.4-.4-1 0-1.4l2.3-2.3l-2.3-2.3c-.4-.4-.4-1 0-1.4c.4-.4 1-.4 1.4 0l2.3 2.3l2.3-2.3c.4-.4 1-.4 1.4 0c.4.4.4 1 0 1.4L13.4 12l2.3 2.3z"/></svg>
            </button>
            <span v-if="isSearching" class="pr-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><circle cx="12" cy="3.5" r="1.5" fill="currentColor" opacity="0"><animateTransform attributeName="transform" calcMode="discrete" dur="2.4s" repeatCount="indefinite" type="rotate" values="0 12 12;90 12 12;180 12 12;270 12 12"/><animate attributeName="opacity" dur="0.6s" keyTimes="0;0.5;1" repeatCount="indefinite" values="1;1;0"/></circle><circle cx="12" cy="3.5" r="1.5" fill="currentColor" opacity="0"><animateTransform attributeName="transform" begin="0.2s" calcMode="discrete" dur="2.4s" repeatCount="indefinite" type="rotate" values="30 12 12;120 12 12;210 12 12;300 12 12"/><animate attributeName="opacity" begin="0.2s" dur="0.6s" keyTimes="0;0.5;1" repeatCount="indefinite" values="1;1;0"/></circle><circle cx="12" cy="3.5" r="1.5" fill="currentColor" opacity="0"><animateTransform attributeName="transform" begin="0.4s" calcMode="discrete" dur="2.4s" repeatCount="indefinite" type="rotate" values="60 12 12;150 12 12;240 12 12;330 12 12"/><animate attributeName="opacity" begin="0.4s" dur="0.6s" keyTimes="0;0.5;1" repeatCount="indefinite" values="1;1;0"/></circle></svg>
            </span>
        </div>
        <button @click="syncProducts()" class="inline-flex justify-center rounded-md border border-transparent bg-primary px-4 py-2 text-sm text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2" :class="[{ 'opacity-50': syncProgress }]" :disabled="syncProgress">
            <svg v-if="syncProgress" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M12 2A10 10 0 1 0 22 12A10 10 0 0 0 12 2Zm0 18a8 8 0 1 1 8-8A8 8 0 0 1 12 20Z" opacity=".5"/><path fill="currentColor" d="M20 12h2A10 10 0 0 0 12 2V4A8 8 0 0 1 20 12Z"><animateTransform attributeName="transform" dur="1s" from="0 12 12" repeatCount="indefinite" to="360 12 12" type="rotate"/></path></svg>
            <span v-else class="flex gap-1">
                <svg  xmlns="http://www.w3.org/2000/svg"  width="20"  height="20"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-refresh"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" /><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" /></svg>
                <span>{{ $t('Sync Product') }}</span>
            </span>
        </button>
    </div>
    <Table :rows="rows" v-if="rows.data.length">
        <TableHeader>
            <TableHeaderRow>
                <TableHeaderRowItem :position="'first'">{{ $t('S.No') }}</TableHeaderRowItem>
                <!-- <TableHeaderRowItem >{{ $t('Status') }}</TableHeaderRowItem> -->
                <TableHeaderRowItem>{{ $t('Image') }}</TableHeaderRowItem>
                <TableHeaderRowItem>{{ $t('Name') }}</TableHeaderRowItem>
                <TableHeaderRowItem>{{ $t('Price') }}</TableHeaderRowItem>
                <TableHeaderRowItem>{{ $t('Retailer ID') }}</TableHeaderRowItem>
                <TableHeaderRowItem>{{ $t('Product Id') }}</TableHeaderRowItem>
                <!-- <TableHeaderRowItem>{{ $t('Price') }}</TableHeaderRowItem> -->
                <TableHeaderRowItem>{{ $t('Synced at') }}</TableHeaderRowItem>
            </TableHeaderRow>
        </TableHeader>
        <TableBody>
            <TableBodyRow v-for="(item, index) in rows.data" :key="index">
                <TableBodyRowItem :position="'first'" class="capitalize">{{ index + 1 }}</TableBodyRowItem>
                <!-- <TableBodyRowItem class="capitalize">{{ item.status }}</TableBodyRowItem> -->
                <TableBodyRowItem class="hidden sm:table-cell">
                    <img v-if="item.image" :src="item.image" alt="" class="w-20 h-20 rounded-full dark:bg-gray-500">
                </TableBodyRowItem>
                <TableBodyRowItem class="hidden sm:table-cell">{{ item.name }}</TableBodyRowItem>
                <TableBodyRowItem class="hidden sm:table-cell">{{ item.price }}</TableBodyRowItem>
                <TableBodyRowItem class="hidden sm:table-cell">{{ item.retailer_id }}</TableBodyRowItem>
                <TableBodyRowItem class="hidden sm:table-cell">{{ item.product_id }}</TableBodyRowItem>
                <!-- <TableBodyRowItem class="hidden sm:table-cell">{{ item.assign_label }}</TableBodyRowItem> -->
                <TableBodyRowItem class="hidden sm:table-cell">{{ item.synced_at }}</TableBodyRowItem>
            </TableBodyRow>
        </TableBody>
    </Table>
    <div v-if="rows.data.length == 0 && !params.search" class="bg-white rounded-xl">
        <div class="p-4 py-8">
            <div class="flex justify-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="72" height="72" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-bag"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
            </div>
            <h3 class="text-center text-lg font-medium mb-4">{{ $t('You don\'t have any Products') }}</h3>
            <div class="flex justify-center">
                <button @click="syncProducts()" class="inline-flex justify-center rounded-md border border-transparent bg-primary px-4 py-2 text-sm text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2" :class="[{ 'opacity-50': syncProgress }]" :disabled="syncProgress">
                    <svg v-if="syncProgress" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M12 2A10 10 0 1 0 22 12A10 10 0 0 0 12 2Zm0 18a8 8 0 1 1 8-8A8 8 0 0 1 12 20Z" opacity=".5"/><path fill="currentColor" d="M20 12h2A10 10 0 0 0 12 2V4A8 8 0 0 1 20 12Z"><animateTransform attributeName="transform" dur="1s" from="0 12 12" repeatCount="indefinite" to="360 12 12" type="rotate"/></path></svg>
                    <span v-else class="flex gap-1">
                        <svg  xmlns="http://www.w3.org/2000/svg"  width="20"  height="20"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-refresh"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" /><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" /></svg>
                        <span>{{ $t('Sync Product') }}</span>
                    </span>
                </button>
            </div>
        </div>
    </div>
    <div v-if="rows.data.length == 0 && params.search" class="bg-white rounded-xl">
        <div class="p-4 py-8">
            <div class="flex justify-center mb-4">
                <svg  xmlns="http://www.w3.org/2000/svg"  width="72"  height="72"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-zoom-exclamation"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /><path d="M10 13v.01" /><path d="M10 7v3" /></svg>
            </div>
            <h3 class="text-center text-lg font-medium mb-4">{{ $t('No Products Found') }}</h3>
        </div>
    </div>
</template>