<template>
    <AppLayout>
        <div class="bg-white md:bg-inherit pt-10 px-4 md:pt-8 md:p-8 rounded-[5px] text-[#000] h-full md:overflow-y-auto">
            <div class="flex justify-between">
                <div>
                    <h2 class="text-xl mb-1">{{ $t('WhatsApp eCommerce Catalog') }}</h2>
                    <p class="mb-6 flex items-center text-sm leading-6">
                        <span class="ml-1 mt-1">{{ $t('Integrate and manage your WhatsApp catalogs') }}</span>
                    </p>
                </div>
            </div>
            <!-- Table Component-->
            <div class="w-full px-2 sm:px-0" v-if="catalogFlag == true">
                <TabGroup :selectedIndex="1">
                    <TabList class="flex space-x-1 rounded-xl text-white bg-primary p-1  max-w-md ">
                        <Tab v-for="category in categories" as="template" :key="category.name" v-slot="{ selected }">
                            <button :class="['w-full rounded-lg text-sm font-medium leading-5','  focus:outline-none ', selected ? 'bg-white shadow text-black' : 'hover:bg-white/[0.12] hover:text-white',]" >
                                <a v-if="category.name != 'Orders'" :href="category.url" class="py-2.5 block">
                                    {{ category.name }}
                                </a>
                                <span v-else class="py-2.5 block">
                                    {{ category.name }}
                                </span>
                            </button>
                        </Tab>
                    </TabList>

                    <TabPanels class="mt-2">
                        <TabPanel v-for="category in categories">
                            <OrdersTable :rows="props.rows" :filters="props.filters" :catalog_id="props.catalog_id" v-if="props.productsCount"/>
                            <div v-if="productsCount == 0" class="bg-white rounded-xl">
                                <div class="p-4 py-8">
                                    <div class="flex justify-center mb-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="72" height="72" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-bag"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                                    </div>
                                    <h3 class="text-center text-lg font-medium mb-4">{{ $t('Sync Products to view Orders!') }}</h3>
                                </div>
                            </div>
                            <div v-if="productsCount != 0 && !props.rows.data.length" class="bg-white rounded-xl">
                                <div class="p-4 py-8">
                                    <div class="flex justify-center mb-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="72" height="72" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-bag"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                                    </div>
                                    <h3 class="text-center text-lg font-medium mb-4">{{ $t('You don\'t have any Orders!') }}</h3>
                                </div>
                            </div>
                        </TabPanel>
                    </TabPanels>
                </TabGroup>
            </div>
            <div class="border flex items-center justify-between p-2 rounded w-full" v-else-if="catalogFlag == false">
                <h1>Add a Catalog to View Products, Orders, Settings & Webhook.</h1>
                <button @click="openModal()" class="rounded-md bg-primary px-3 py-2 text-sm text-white shadow-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">{{ $t('Add Catalog') }}</button>
            </div>
        </div>

        <Modal :label="label" :isOpen=isOpenFormModal>
            <div class="mt-5 grid grid-cols-1 gap-x-6 gap-y-4">
                <form @submit.prevent="submitForm()" class="grid gap-x-6 gap-y-4 sm:grid-cols-6">
                    <FormInput v-model="form.catalog_id" :error="form.errors.catalog" :name="$t('Catalog Id')" :type="'text'" :class="'sm:col-span-6'" :disabled="formMethod === 'put' ? true : false"/>
                    <!-- <FormSelect v-model="form.role" :error="form.errors.role" :options="roleOptions" :name="$t('Role')" :class="'sm:col-span-6'" :placeholder="$t('Select Role')"/> -->
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
    </AppLayout>
</template>
<script setup>
    import AppLayout from "./../Layout/App.vue";
    import { ref } from 'vue';
    import { Link, useForm } from "@inertiajs/vue3";
    import OrdersTable from '@/Components/Tables/OrdersTable.vue';
    import Modal from '@/Components/Modal.vue';
    import FormInput from '@/Components/FormInput.vue';
    import { trans } from 'laravel-vue-i18n';
    import { TabGroup, TabList, Tab, TabPanels, TabPanel } from '@headlessui/vue'
    const props = defineProps(['rows','title','catalog_id','productsCount']);
    const isOpenFormModal = ref(false);
    const categories = ref([{'name':'Products','url':'/ecommerce/products'},{'name':'Orders','url':'/ecommerce/orders'},{'name':'Settings','url':'/ecommerce/settings'},{'name':'Webhook','url':'/ecommerce/webhook'}]);
    let selected = 'Orders';
    const formUrl = ref('/store/catalog');
    const formMethod = ref('post');
    const label = ref('Add Catalog');
    const catalogFlag = props.catalog_id ? ref(true) : ref(false);
    const form = useForm({
        catalog_id: null,
    });

    const openModal = (key, formData = {}) => {
        label.value = trans('Add Catalog');
        formUrl.value = '/store/catalog';
        formMethod.value = 'post';
        if (key) {
            label.value = trans('Add Catalog');
            formUrl.value = '/store/catalog/' + key.id;
            formMethod.value = 'put';
            form.catalog_id = key.catalog_id;
            isOpenFormModal.value = true;
        } else {
            form.catalog_id = null;
            form.errors.catalog = null;
            isOpenFormModal.value = true;
        }
    }
    const submitForm = () => {
        form.processing = true;
        if(!form.catalog_id){
            if(!form.catalog_id) form.errors.catalog = 'This Field is required';
            form.processing = false;
            return false;
        }
        if(formMethod.value === 'post'){
            form.post(formUrl.value, {
                onFinish: () => {
                    form.processing = false;
                    isOpenFormModal.value = false;
                    catalogFlag.value = props.catalog_id ? true : false;
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
</script>