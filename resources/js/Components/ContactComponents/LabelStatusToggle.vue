<script setup>
    import { ref, onMounted, onUnmounted } from 'vue';

    const props = defineProps({
        rows:Object,
        languages: Object,
        currentLanguage: String,
        status: String,
        rowCount: Number,
        filters: Object
    })

    const emit = defineEmits(['status']);

    const isOpen = ref(false);

    const toggleDropdown = () => {
        isOpen.value = !isOpen.value;
    }

    const handleClickOutside = (event) => {
        if (isOpen.value && !event.target.closest('.status-dd')) {
            isOpen.value = false;
        }
    }

    const capitalizeString = (str) => {
        // Check if the string is empty or null
        if (!str) return '';
        
        // Capitalize the first character and concatenate it with the rest of the string
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    const labelContactFilter = (type) => {
        emit('status',type)
    }

    onMounted(() => {
        document.body.addEventListener('click', handleClickOutside);
    });

    onUnmounted(() => {
        document.body.removeEventListener('click', handleClickOutside);
    });
</script>
<template>
    <div class="relative text-sm">
        <div @click="toggleDropdown()" class="status-dd">
            <div class="cursor-pointer flex items-center space-x-1 bg-slate-50 w-[fit-content] h-[fit-content] rounded-md p-1 px-2 text-sm">
                <span class="capitalize overflow-ellipsis overflow-hidden w-20" style="max-width: fit-content;white-space: nowrap;">{{ $t(capitalizeString(props.status)) }}</span>
                <span class="text-slate-500">{{ props.rowCount }}</span>
                <span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" fill-rule="evenodd" d="M16.53 8.97a.75.75 0 0 1 0 1.06l-4 4a.75.75 0 0 1-1.06 0l-4-4a.75.75 0 1 1 1.06-1.06L12 12.44l3.47-3.47a.75.75 0 0 1 1.06 0" clip-rule="evenodd"/></svg>
                </span>
            </div>
        </div>
        <div v-if="isOpen" class="absolute bg-white z-10 px-1 w-full py-2 shadow rounded-md text-black" style="max-width: max-content;">
            <div>
                <div v-for="item in rows" @click="labelContactFilter(item.name)" class="block px-2 py-1 cursor-pointer hover:bg-slate-100 rounded-md overflow-ellipsis overflow-hidden w-24" style="white-space: nowrap;">
                    {{ $t(item.name)}} 
                </div>
                <div @click="labelContactFilter('all')" class="block px-2 py-1 cursor-pointer hover:bg-slate-100 rounded-md overflow-ellipsis overflow-hidden w-24" style="white-space: nowrap;">
                    {{ $t('All')}} 
                </div>
            </div>
        </div>
    </div>
</template>