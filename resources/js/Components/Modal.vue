<script setup>
    import { ref } from 'vue';
    import { TransitionRoot, TransitionChild, Dialog, DialogPanel, DialogTitle, DialogDescription } from '@headlessui/vue';

    const props = defineProps({
        label: String,
        isOpen: Boolean,
        closeBtn: Boolean,
        description: String,
        extendMaxWidth:Boolean,
        customStyle:Boolean
    })

    const emit = defineEmits(['close']);

    function closeModal() {
        emit('close', true);
    }
</script>
<template>
    <TransitionRoot appear :show="props.isOpen" as="template">
        <Dialog as="div" class="relative z-10">
            <TransitionChild
                as="template"
                enter="duration-300 ease-out"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="duration-200 ease-in"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div class="fixed inset-0 bg-black bg-opacity-25" />
            </TransitionChild>

            <div class="fixed inset-0 overflow-y-auto">
                <div
                class="flex min-h-full items-center justify-center p-4 text-center"
                >
                <TransitionChild
                    as="template"
                    enter="duration-300 ease-out"
                    enter-from="opacity-0 scale-95"
                    enter-to="opacity-100 scale-100"
                    leave="duration-200 ease-in"
                    leave-from="opacity-100 scale-100"
                    leave-to="opacity-0 scale-95"
                >
                    <DialogPanel
                    class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white text-left align-middle shadow-xl transition-all" :style="{ maxWidth : extendMaxWidth ? '700px' : ''}"
                    >
                    <div class="justify-between items-center bg-gray-50 px-4 py-3" :class="!props.description ? 'flex' : ''">
                        <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900">
                            {{ props.label }}
                        </DialogTitle>

                        <DialogDescription class="text-mauve11 mt-[10px] text-[15px] leading-normal" v-if="props.description">
                            {{ props.description }}
                        </DialogDescription>

                        <span @click="closeModal" v-if="closeBtn === true" class="bg-slate-100 rounded-full p-1 hover:shadow cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="black" stroke-linecap="round" stroke-linejoin="round" d="M17 7L7 17M7 7l10 10"/></svg>
                        </span>
                    </div>
                    <div :class="[!customStyle ? 'px-4 pb-4' : '']">
                        <slot />
                    </div>
                    </DialogPanel>
                </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>