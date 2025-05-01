<script setup>
    import axios from 'axios';
    import { ref, computed } from 'vue';
    import debounce from 'lodash/debounce';
    import { router, useForm, usePage } from "@inertiajs/vue3";
    import AlertModal from '@/Components/AlertModal.vue';
    // import { useAlertModal } from '@/Composables/useAlertModal';
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
    import DrawerProductsTable from '@/Components/Tables/DrawerProductsTable.vue';
    const props = defineProps(['rows','filters','catalog_id']);
    const drawerVisible = ref(false);
    const isSearching = ref(false);

    const params = ref({
        search: props.filters?.search,
        page: props.filters?.page,
    });

    const search = debounce(() => {
        isSearching.value = true;
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
   
    const singleProducts = ref([]);
    const shippingAddress = ref([]);
    const orderTotalAmount = ref(null);
    const orderSubTotalAmount = ref(null);
    const price_details = ref(null);
    const orderCurrency = ref(null);
    const orderId = ref(null);
    const orderStatus = ref(null);
    const orderTransactionId = ref(null);
    const viewProducts = async(item) => {
        var formData = new FormData();
        formData.append('product_retailer_ids',JSON.stringify(item.product_retailer_ids));
        formData.append('catalog_id',item.catalog_id);
        axios.post('/get/orderProducts',formData).then(res => {
            try {
                drawerVisible.value = true;
                res.data.products.data.forEach(ele=>{
                    var inx = item.product_retailer_id_with_quantity.findIndex(e => { return e.product_retailer_id == ele.retailer_id});
                    ele.quantity = item.product_retailer_id_with_quantity[inx].quantity;
                    ele.total = item.product_retailer_id_with_quantity[inx].productsPrice;
                })
                singleProducts.value = res.data.products;
                shippingAddress.value = item.shipping_address ? JSON.parse(item.shipping_address) : null;
                orderSubTotalAmount.value = (Math.round(item.total * 100) / 100).toFixed(2);
                orderTotalAmount.value = item.total;
                orderCurrency.value = item.currency;
                orderId.value = item.id;
                orderStatus.value = item.status;
                orderTransactionId.value = item.payment_metadata ? JSON.parse(item.payment_metadata).payment.transaction.id : ''
                price_details.value = item.price_details ? JSON.parse(item.price_details) : null;
                if (price_details.value) {
                    orderTotalAmount.value = (Math.round((orderTotalAmount.value + ((price_details.value.tax + price_details.value.shipping) - price_details.value.discount)) * 100) / 100).toFixed(2);
                }else{
                    orderTotalAmount.value = (Math.round(item.total * 100) / 100).toFixed(2);
                }
            } catch (error) {
                console.log(error);
            }
        })
    }
</script>
<template>
    <div class="flex mb-6 mt-6 justify-between items-center">
    </div>
    <Table :rows="rows">
        <TableHeader>
            <TableHeaderRow>
                <TableHeaderRowItem :position="'first'">{{ $t('S.No') }}</TableHeaderRowItem>
                <!-- <TableHeaderRowItem >{{ $t('Status') }}</TableHeaderRowItem> -->
                <!-- <TableHeaderRowItem>{{ $t('Image') }}</TableHeaderRowItem> -->
                <TableHeaderRowItem>{{ $t('Phone number') }}</TableHeaderRowItem>
                <TableHeaderRowItem>{{ $t('Buyer') }}</TableHeaderRowItem>
                <TableHeaderRowItem>{{ $t('Total Amount') }}</TableHeaderRowItem>
                <TableHeaderRowItem>{{ $t('Currency') }}</TableHeaderRowItem>
                <TableHeaderRowItem>{{ $t('Status') }}</TableHeaderRowItem>
                <TableHeaderRowItem>{{ $t('Ordered at') }}</TableHeaderRowItem>
                <TableHeaderRowItem>{{ $t('Updated at') }}</TableHeaderRowItem>
                <TableHeaderRowItem>{{ $t('Action') }}</TableHeaderRowItem>
            </TableHeaderRow>
        </TableHeader>
        <TableBody>
            <TableBodyRow v-for="(item, index) in rows.data" :key="index">
                <TableBodyRowItem :position="'first'" class="capitalize">{{ index + 1 }}</TableBodyRowItem>
                <!-- <TableBodyRowItem class="capitalize">{{ item.status }}</TableBodyRowItem> -->
                <!-- <TableBodyRowItem class="hidden sm:table-cell">
                    <img v-if="item.image" :src="item.image" alt="" class="w-20 h-20 rounded-full dark:bg-gray-500">
                </TableBodyRowItem> -->
                <TableBodyRowItem class="hidden sm:table-cell">{{ item.phone_number }}</TableBodyRowItem>
                <TableBodyRowItem class="hidden sm:table-cell">{{ item.buyer }}</TableBodyRowItem>
                <TableBodyRowItem class="hidden sm:table-cell">{{ item.total }}</TableBodyRowItem>
                <TableBodyRowItem class="hidden sm:table-cell">{{ item.currency }}</TableBodyRowItem>
                <TableBodyRowItem class="hidden sm:table-cell">{{ item.status }}</TableBodyRowItem>
                <TableBodyRowItem class="hidden sm:table-cell">{{ item.ordered_at }}</TableBodyRowItem>
                <TableBodyRowItem class="hidden sm:table-cell">{{ item.created_at }}</TableBodyRowItem>
                <TableBodyRowItem :className="'text-center'">
                    <button class="rounded-md bg-primary px-3 py-2 text-sm text-white shadow-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 text-center" @click="viewProducts(item)">
                        {{ $t('View') }}
                    </button>
                </TableBodyRowItem>
            </TableBodyRow>
        </TableBody>
    </Table>

    <!-- <Drawer /> -->
    <div class="right-drawer" :style="{ width: drawerVisible ? '60vw' : '0', paddingLeft: drawerVisible ? '0' : '0',}">
        <div class="flex justify-between py-5 px-8 border-b">
            <h1>Order Products</h1>
            <button class="close" @click="drawerVisible = false">
                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="#034737"  class="icon icon-tabler icons-tabler-filled icon-tabler-xbox-x"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 2c5.523 0 10 4.477 10 10s-4.477 10 -10 10s-10 -4.477 -10 -10s4.477 -10 10 -10m3.6 5.2a1 1 0 0 0 -1.4 .2l-2.2 2.933l-2.2 -2.933a1 1 0 1 0 -1.6 1.2l2.55 3.4l-2.55 3.4a1 1 0 1 0 1.6 1.2l2.2 -2.933l2.2 2.933a1 1 0 0 0 1.6 -1.2l-2.55 -3.4l2.55 -3.4a1 1 0 0 0 -.2 -1.4" /></svg></button>
        </div>
        <div class="w-full overflow-y-auto" style="height: calc(100% - 65px);scrollbar-width: none;">
            <div class="flex justify-between py-5 px-8 border-b gap-4 flex-col md:flex-row">
                <div class="flex flex-col gap-2 w-full p-6 bg-white border border-gray-200 rounded-lg shadow-sm h-full">
                    <h1 class="text-1xl pb-5 flex justify-between">
                        <span>
                            Payment Info
                        </span>
                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-receipt"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16l-3 -2l-2 2l-2 -2l-2 2l-2 -2l-3 2m4 -14h6m-6 4h6m-2 4h2" /></svg>
                    </h1>
                    <div class='flex justify-between'>
                        <span class="font-normal whitespace-nowrap">Order Id : </span>
                        <span class="text-right">{{ '#' + orderId }}</span>
                    </div>
                    <div class='flex justify-between'>
                        <span class="font-normal whitespace-nowrap">Total Amount : </span>
                        <span class="text-right">{{ orderTotalAmount }}</span>
                    </div>
                    <div class='flex justify-between'>
                        <span class="font-normal whitespace-nowrap">Payment Status : </span>
                        <span class="text-right">
                            <span class="px-3 py-1 rounded-[5px] text-white text-xs" :class="[orderTransactionId ? 'bg-green-700' : 'bg-gray-400']" >{{ orderStatus }}</span>
                            <!-- <span class="bg-red-700 px-3 py-1 rounded-[5px] text-white text-xs">{{ $t('unpaid') }}</span> -->
                    </span>
                    </div>
                    <div class='flex justify-between'>
                        <span class="font-normal whitespace-nowrap">Payment Reference Id : </span>
                        <span class="text-right">{{ orderTransactionId ? orderTransactionId : '-' }}</span>
                    </div>
                </div>
                <div class="flex flex-col gap-2 w-full p-6 bg-white border border-gray-200 rounded-lg shadow-sm h-full">
                    <h1 class="text-1xl pb-5 flex justify-between">
                        <span>
                            Shipping Address
                        </span>
                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-map-pins"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10.828 9.828a4 4 0 1 0 -5.656 0l2.828 2.829l2.828 -2.829z" /><path d="M8 7l0 .01" /><path d="M18.828 17.828a4 4 0 1 0 -5.656 0l2.828 2.829l2.828 -2.829z" /><path d="M16 15l0 .01" /></svg>
                    </h1>
                    <div class='flex justify-between' v-for="(item, index) in shippingAddress">
                        <span class="font-normal whitespace-nowrap capitalize">{{ index }}</span>
                        <span class="text-right">{{ item }}</span>
                    </div>
                </div>
            </div>
            <h1 class="text-1xl py-5 px-8">
                <span>
                    Product Summary
                </span>
            </h1>
            <DrawerProductsTable :rows="singleProducts" :filters="props.filters" :url="true" v-if="drawerVisible == true" />
            <div class="flex justify-end py-5 px-8 mt-6 border-t ">
                <div class="w-1/2">
                    <h1 class="text-1xl pb-4">
                        Order Summary
                    </h1>
                    <div class="flex flex-col gap-2">
                        <div class='flex justify-between' :class="[!price_details ? 'border-b-2 pb-2' : '']">
                            <span class="font-normal whitespace-nowrap">Sub Total : </span>
                            <span class="text-right">{{ orderCurrency + ' ' + orderSubTotalAmount }}</span>
                        </div>
                        <div class='flex justify-between' v-if="price_details">
                            <span class="font-normal whitespace-nowrap">Tax : </span>
                            <span class="text-right">{{ orderCurrency + ' ' + (Math.round(price_details?.tax * 100) / 100).toFixed(2) }}</span>
                        </div>
                        <div class='flex justify-between' v-if="price_details">
                            <span class="font-normal whitespace-nowrap">Shipping : </span>
                            <span class="text-right">{{ orderCurrency + ' ' + (Math.round(price_details?.shipping * 100) / 100).toFixed(2) }}</span>
                        </div>
                        <div class='flex justify-between border-b-2 pb-2' v-if="price_details">
                            <span class="font-normal whitespace-nowrap">Discount : </span>
                            <span class="text-right">{{ orderCurrency + ' ' + (Math.round(price_details?.discount * 100) / 100).toFixed(2) }}</span>
                        </div>
                        <div class='flex justify-between'>
                            <span class="font-normal whitespace-nowrap">Total : </span>
                            <span class="text-right">{{ orderCurrency + ' ' + orderTotalAmount }}</span>
                        </div>
                    </div>
                </div>
            </div>            
        </div>
    </div>
    <div class="drawer-mask" :style="{ width: drawerVisible ? '100vw' : '0', opacity: drawerVisible ? '0.6' : '0',}" @click="drawerVisible = false"></div>
    <!-- <Drawer /> -->

</template>
<style scoped>

.close {
  background: white;
  border: 0;
  cursor: pointer;
}

.right-drawer {
  position: absolute;
  top: 0;
  right: 0;
  width: 0; /* initially */
  overflow: hidden;
  height: 100vh;
  padding-left: 0; /* initially */
  border-left: 1px solid whitesmoke;
  background: white;
  z-index: 200;
  transition: all 0.2s;
}

.drawer-mask {
  position: absolute;
  left: 0;
  top: 0;
  width: 0; /* initially */
  height: 100vh;
  background: #000;
  opacity: 0.3;
  z-index: 199;
  transition: opacity 0.2s;
}
</style>
