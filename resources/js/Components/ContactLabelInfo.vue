<script setup>
    import axios from 'axios';
    import { ref, watchEffect } from 'vue';
    import { router, useForm } from '@inertiajs/vue3';
    import FormModal from '@/Components/FormModal.vue';
    import { trans } from 'laravel-vue-i18n';
import { ListPlusIcon } from 'lucide-vue-next';
    
    const props = defineProps(['label']);
    const label = ref(props.label);

    watchEffect(() => {
        label.value = props.label;
    });

    const isOpenFormModal = ref(false);
    const form = ref({
        name: label.value.name,
    });

    const formInputs = [
        {
            inputType: 'FormInput',
            name: 'name',
            label: trans('name'),
            type: 'text',
            className: 'sm:col-span-6',
        },
    ];

    const form2 = useForm({'test': null});

    const deleteRow = async() => {
        router.visit('/contact-labels', {
            method: 'delete',
            data: { 'uuids': [ label.value.uuid ]},
            preserveState: true
        })
    }

    const openModal = () => {
        isOpenFormModal.value = true;
    }
</script>
<template>
    <div>
        <div class="pt-20">
            <div class="flex justify-center space-x-8 items-center pb-6 pr-20 border-gray-300 border-b">
                <div>
                    <div class="rounded-full p-1 bg-white">
                        <div class="rounded-full text-3xl flex justify-center items-center h-24 w-24 capitalize">{{ label.name.substring(0, 1) }}</div>
                    </div>
                </div>
                <div>
                    <h1 class="text-3xl">{{ label.name }}</h1>
                    <div class="flex space-x-3 mt-4">
                        <button class="bg-gray-200 py-2 px-4 h-9 rounded-md flex items-center" @click="openModal('edit')">
                            <span class="text-[14px]">{{ $t('Edit') }}</span>
                        </button>
                        <button @click="deleteRow()" class="bg-gray-200 py-2 px-4 h-9 rounded-md flex items-center">
                            <span class="text-[14px]">{{ $t('Delete') }}</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="pr-20 border-gray-300 border-b py-4">
                <div class="grid grid-cols-2 space-x-8 text-[14px]">
                    <div class="text-right text-slate-500 pb-2">
                        <span>{{ $t('Label name') }}</span>
                    </div>
                    <div>
                        <span>{{ label.name }}</span>
                    </div>
                    <div class="text-right text-slate-500 pb-2">
                        <span>{{ $t('Total contacts') }}</span>
                    </div>
                    <div>
                        <span class="p-1 bg-gray-50 text-xs rounded-lg text-gray-600">{{ label.contact_count }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <FormModal 
        v-model="isOpenFormModal" 
        :label="$t('Edit label')" 
        :url="'/contact-labels/' + label?.uuid" 
        :form="form"
        :formInputs="formInputs"
    />
</template>