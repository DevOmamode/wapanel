<template>
    <template v-if="props.title.toLowerCase() != 'chats'">
    <div :class="`hidden w-full transition-all  duration-400 ease-in-out fixed top-0 left-[15.5rem] xl:left-[16.1rem] pe-[19.5rem] right-0 z-10 bg-primary text-white py-4 h-20 md:flex justify-end items-center gap-10`">
        <div class="relative">
            <span class="pi pi-bell"></span>
            <span class="absolute top-[-0.2em] right-[-0.5rem] text-[0.7rem] w-4 h-4 text-white bg-red-500 flex justify-center rounded-xl">1</span>
        </div>
        <span @click="openModal">
                <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"><path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10s10-4.477 10-10S17.523 2 12 2"/><path d="M4.271 18.346S6.5 15.5 12 15.5s7.73 2.846 7.73 2.846M12 12a3 3 0 1 0 0-6a3 3 0 0 0 0 6"/></g></svg>
        </span>
    </div>
    <ProfileModal :user="props.user" :organization="props.organization" :isOpen="isProfileOpen" role="user" @close="closeModal()"/>
    </template>
</template>
<script setup>
import { defineProps, ref } from 'vue';
import ProfileModal from '@/Components/ProfileModal.vue';

const props = defineProps({
    title: {
        type: String,
        default: "",
    },
    user: {
        type: Object,
        required: true,
    },
    organization: {
        type: Object,
        required: true,
    }
});

const isProfileOpen = ref(false);

function openModal() {
        isProfileOpen.value = true;
}

const closeModal = () => {
        isProfileOpen.value = false;
}
</script>