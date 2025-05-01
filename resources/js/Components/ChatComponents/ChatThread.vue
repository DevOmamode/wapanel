<script setup>
    import { ref, computed } from 'vue';
    import ChatBubble from '@/Components/ChatComponents/ChatBubble.vue';
    import Modal from '@/Components/Modal.vue';

    const props = defineProps({
        rows: {
            type: Object,
            required: true,
        },
        orgProductSettings : Object
    });
    const isOpenFormModal = ref(false)
    const shippingDetailsJson = ref({});

    const shippingDetails = (data) => {
        shippingDetailsJson.value = JSON.parse(data.details);
        isOpenFormModal.value = true
    }
    

</script>
<template>
    <div v-if="rows.length > 0" class="py-4 md:py-4 relative px-6 md:px-10">
        <div v-for="(chat, index) in rows" class="flex flex-grow flex-col" :class="chat[0].type === 'ticket' ? 'justify-center' : 'justify-end'">
            <ChatBubble v-if="chat[0].type === 'chat'" :key="index" :content="chat[0].value" :type="chat[0].value.type" @shippingDetails="shippingDetails" :orgProductSettings="orgProductSettings" />
            <div v-if="chat[0].type === 'ticket'" class="py-2">
                <div class="text-center font-light text-sm border-b border-t py-2 border-dashed border-black">
                    <div>{{ chat[0].value.description }} </div>
                    <div class="text-xs">{{ chat[0].value.created_at }}</div>
                </div>
            </div>
            <div v-if="chat[0].type === 'notes'" class="py-2 bg-orange-100 my-2 rounded-lg p-2 w-[fit-content] ml-auto">
                <div class="text-right font-light text-sm">
                    <div>{{ chat[0].value.content }}</div>
                    <div class="flex items-center justify-between mt-2 space-x-4">
                        <!--<span v-if="props.type === 'outbound' && content.user" class="text-gray-500 text-xs text-right leading-none">Sent By: {{ content.user?.first_name + ' ' + content.user?.last_name }}</span>-->
                        <p class="text-gray-500 text-xs text-right leading-none">{{ chat[0].value.created_at }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <Modal :label="'Shipping Details'" :isOpen=isOpenFormModal :closeBtn="true" @close="isOpenFormModal = false">
        <div class="px-3">
            <div class='mb-2' v-for="(item, index) in shippingDetailsJson">
                <span class="text-sm font-normal whitespace-nowrap capitalize">{{ index }}</span>
                <br>
                <span class="font-medium">{{ item }}</span>
            </div>
        </div>
    </Modal>
</template>