<template>
  <AppLayout>
    <div class="bg-white md:bg-inherit pt-0 px-4 md:pt-8 md:p-8 rounded-lg text-black h-full overflow-y-auto">
      <div class="md:flex justify-between hidden">
        <div>
          <h1 class="text-xl mb-1">{{ $t('Tools') }}</h1>
          <p class="mb-6 flex items-center text-sm leading-6 text-gray-600">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24">
              <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11v5m0 5a9 9 0 1 1 0-18a9 9 0 0 1 0 18Zm.05-13v.1h-.1V8h.1Z"/>
            </svg>
            <span class="ml-1 mt-1">{{ $t('Tools information') }}</span>
          </p>
         
        </div>
      </div>

      <div class="flex flex-col p-4 mb-6 bg-white border border-slate-200 rounded-lg">
        <div class="flex items-center  p-2 mb-1">
          <h1 class="text-2xl font-bold">{{ $t('Tools to Grow your Business') }}</h1>
          
        </div>
        <div class="flex items-center  p-4 mb-0">
          <p>Enhance customer engagement with our WhatsApp Widget—enabling instant chat support, quick responses, and seamless communication directly from your website.</p>
        </div>
        
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
          <div class="rounded"></div>
          <div class="rounded"></div>
          <div class="rounded"></div>
        </div>
        
        <div class="p-4 rounded mt-6">
          <Link href="tools/whatsapp-widget" class="bg-primary py-2 px-3 rounded-lg text-white">
            {{ $t('WhatsApp Widget  ') }}
          </Link>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from "./../Layout/App.vue";
import { Link } from "@inertiajs/vue3";
</script>

<style scoped>
.bg-blue-600 {
  background: #114ABB;
  color: white;
}
</style>