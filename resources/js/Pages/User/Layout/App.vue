<template>
    <MobileSidebar :user="user" :config="config" :organization="organization" :organizations="organizations" :title="currentPageTitle" :displayCreateBtn="displayCreateBtn" :displayTopBar="viewTopBar"></MobileSidebar>
    <DesktopSidebar :user="user" :organization="organization" :title="currentPageTitle" :shortenedMenu="shortenedMenu" :displayTopBar="viewTopBar"></DesktopSidebar>
    <div class="md:mt-0 md:pt-0 flex md:h-screen w-full tracking-[0.3px] bg-gray-300/10" :class="viewTopBar === false ? 'mt-0 pt-0' : ''">
        <Sidebar :user="user" @toggled="effectToggle" :config="config" :organization="organization" :organizations="organizations" :unreadMessages="unreadMessages"></Sidebar>
        <div class="md:min-h-screen md:mt-20 flex flex-col w-full min-w-0">
            <slot :user="user" :toggleNavBar="toggleTopBar" @testEmit="doSomething"></slot>
        </div>
    </div>

    <audio ref="audioPlayer" allow="autoplay"></audio>
</template>
<script setup>
    import { usePage } from "@inertiajs/vue3";
    import Sidebar from "./Sidebar.vue";
    import { defineProps, ref, computed, watch, onMounted } from 'vue';
    import { toast } from 'vue3-toastify';
    import MobileSidebar from "./MobileSidebar.vue";
    import DesktopSidebar from "./DesktopSidebar.vue";
    import 'vue3-toastify/dist/index.css';
    import { getEchoInstance } from '../../../echo';

    const viewTopBar = ref(true);
    const user = computed(() => usePage().props.auth.user);
    const config = computed(() => usePage().props.config);
    const organization = computed(() => usePage().props.organization);
    const organizations = computed(() => usePage().props.organizations);
    const currentPageTitle = computed(() => usePage().props.title);
    const displayCreateBtn = computed(() => usePage().props.allowCreate);
    const unreadMessages = ref(usePage().props.unreadMessages);
    const audioPlayer = ref(null);
    const shortenedMenu = ref(/true/.test(localStorage.getItem('MenuOpen')));

    watch(() => [usePage().props.flash, { deep: true }], () => {
        if(usePage().props.flash.status != null){
            toast(usePage().props.flash.status.message, {
                autoClose: 3000,
            });
        }
    });

    const toggleTopBar = () => {
        viewTopBar.value = !viewTopBar.value;
    };

    function effectToggle(v){
        shortenedMenu.value = v;
    }

    const getValueByKey = (key) => {
        const found = config.value.find(item => item.key === key);
        return found ? found.value : '';
    };

    const setupSound = () => {
        const settings = organization.value.metadata ? JSON.parse(organization.value.metadata) : {};
        const notifications = settings.notifications || {};

        if (notifications?.enable_sound && audioPlayer.value) {
            audioPlayer.value.src = notifications?.tone;
            audioPlayer.value.volume = notifications?.volume || 1.0;
        }
    };

    const playSound = () => {
        if (audioPlayer.value) {
            audioPlayer.value.play().catch((error) => {
                console.warn("Audio playback failed:", error);
            });
        }
    };

    onMounted(() => {
        setupSound();

        const echo = getEchoInstance(
            getValueByKey('pusher_app_key'),
            getValueByKey('pusher_app_cluster')
        );

        echo.channel('chats.ch' + organization.value.id).listen('NewChatEvent', (event) => {
            const chat = event.chat;

            if (chat[0].value.deleted_at == null && chat[0].value.type === 'inbound') {
                playSound(); // Play sound for inbound messages
                unreadMessages.value += 1; // Increment unread messages count
            }
        });
    });
</script>