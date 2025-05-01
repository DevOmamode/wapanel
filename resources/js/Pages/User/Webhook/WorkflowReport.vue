<script setup>
    import AppLayout from "./../Layout/App.vue";
    import { Link, router , useForm} from "@inertiajs/vue3";
    import { ref, onMounted } from 'vue';
    import { trans } from 'laravel-vue-i18n';
    import Modal from '@/Components/Modal.vue';
    import WebhookReportTable from '@/Components/Tables/WebhookReportTable.vue';
    import { toast } from 'vue3-toastify';
    import 'vue3-toastify/dist/index.css';
    const props = defineProps(['rows','filters']);
</script>
<template>
    <AppLayout>
        <div class="bg-white md:bg-inherit pt-10 px-4 md:pt-8 md:p-8 rounded-[5px] text-[#000] h-full md:overflow-y-auto">
            <div class="flex justify-between">
                <div>
                    <h2 class="text-xl mb-1">{{ $t('WhatsApp Webhook Workflow Report') }}</h2>
                    <p class="mb-6 flex items-center text-sm leading-6">
                        <span class="ml-1 mt-1">{{ $t('All your webhook workflow call report in one place') }}</span>
                    </p>
                </div>
                <div>
                    <Link rel="noopener noreferrer" href="/whatsapp/workflow" class="rounded-md bg-primary px-3 py-2 text-sm text-white shadow-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        <span>{{ $t('Back') }}</span>
                    </Link>
                </div>
            </div>
            <div>
                <WebhookReportTable :rows="props.rows" :filters="props.filters"></WebhookReportTable>
            </div>
        </div>
    </AppLayout>
</template>