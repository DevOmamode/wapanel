<template>
    <MobileSidebar :user="user" :config="config" :title="currentPageTitle" :displayCreateBtn="displayCreateBtn"></MobileSidebar>
    <DesktopSidebar :user="user" :title="currentPageTitle" :organization="organization"></DesktopSidebar>
    <div class="pt-10 mt-4 md:mt-0 md:pt-0 flex md:h-screen w-full tracking-[0.3px] bg-gray-300/10">
        <Sidebar :user="user" :config="config"></Sidebar>
        <div class="md:min-h-screen  md:mt-20 flex flex-col w-full min-w-0">
            <slot :user="user"></slot>
        </div>
    </div>
</template>
<script setup>
    import { usePage } from "@inertiajs/vue3";
    import Sidebar from "./Sidebar.vue";
    import MobileSidebar from "./MobileSidebar.vue";
    import DesktopSidebar from "./DesktopSidebar.vue";
    import { ref, computed, onMounted, watch } from 'vue';
    import { toast } from 'vue3-toastify';
    import 'vue3-toastify/dist/index.css';

    const { props } = usePage();

    const user = computed(() => usePage().props.auth.user);
    const config = computed(() => usePage().props.config);
    const currentPageTitle = computed(() => usePage().props.title);
    const organization = computed(() => usePage().props.organization);
    const displayCreateBtn = computed(() => usePage().props.allowCreate);

    watch(() => [usePage().props.flash, { deep: true }], () => {
        if(usePage().props.flash.status != null){
            toast(usePage().props.flash.status.message, {
                autoClose: 3000,
            });
        }
    });
</script>