<template>
    <div v-if="getValueByKey('logo') === null" class="flex items-center justify-between px-5 pt-5 h-20 mb-4">
        <h2 v-if="!menuIconsOnly" class="ml-2 text-2xl">{{ getValueByKey('company_name') }}</h2>
        <LangToggle v-if="!menuIconsOnly" class="text-black hidden" :languages="languages" :currentLanguage="currentLanguage" />
        <span v-if="isSidebarOpen === true" @click="closeSidebar()">
            <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="2"><path d="M5 5L12 5L19 5"><animate fill="freeze" attributeName="d" dur="0.4s" values="M5 5L12 5L19 5;M5 5L12 12L19 5"/></path><path d="M5 12H19"><animate fill="freeze" attributeName="d" dur="0.4s" values="M5 12H19;M12 12H12"/></path><path d="M5 19L12 19L19 19"><animate fill="freeze" attributeName="d" dur="0.4s" values="M5 19L12 19L19 19;M5 19L12 12L19 19"/></path></g></svg>
        </span>
    </div>
    <div v-else class="flex items-center justify-between px-5 pt-5 h-20 mb-1">
        <Link href="/dashboard">
            <img :src="'/media/' + getValueByKey('logo')" alt="{{ getValueByKey('company_name') }}" class="w-32 object-contain h-full ps-2">
        </Link>
        <span v-if="isSidebarOpen === true" @click="closeSidebar()">
            <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="2"><path d="M5 5L12 5L19 5"><animate fill="freeze" attributeName="d" dur="0.4s" values="M5 5L12 5L19 5;M5 5L12 12L19 5"/></path><path d="M5 12H19"><animate fill="freeze" attributeName="d" dur="0.4s" values="M5 12H19;M12 12H12"/></path><path d="M5 19L12 19L19 19"><animate fill="freeze" attributeName="d" dur="0.4s" values="M5 19L12 19L19 19;M5 19L12 12L19 19"/></path></g></svg>
        </span>
        <LangToggle v-if="!menuIconsOnly" class="text-black" :languages="languages" :currentLanguage="currentLanguage" />
    </div>
    <div class="flex-grow space-y-3 px-2 overflow-y-scroll scrollbar-thin">
        <div class="flex-1">
            <ul class="pt-2 space-y-1 text-sm mb-2">
                <div class="px-4 py-4">
                <hr>
                </div>
                <li class="text-black hover:bg-green-50 hover:text-green-500 rounded-[5px] px-2 truncate" :class="$page.url.startsWith('/dashboard') ? 'bg-green-50 text-green-500' : ''">
                    <Link rel="noopener noreferrer" href="/dashboard" class="flex items-center p-2 space-x-3 rounded-md">
                        <svg class="text-green-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 11.452V16.8c0 1.12 0 1.68.218 2.109c.192.376.497.682.874.873c.427.218.987.218 2.105.218h9.606c1.118 0 1.677 0 2.104-.218a2 2 0 0 0 .875-.873c.218-.428.218-.987.218-2.105v-5.352c0-.534 0-.801-.065-1.05a1.998 1.998 0 0 0-.28-.617c-.145-.213-.345-.39-.748-.741l-4.8-4.2c-.746-.653-1.12-.98-1.54-1.104c-.37-.11-.764-.11-1.135 0c-.42.124-.792.45-1.538 1.102L5.093 9.044c-.402.352-.603.528-.747.74a2 2 0 0 0-.281.618C4 10.65 4 10.918 4 11.452Z"/></svg>
                        <span :class="(menuIconsOnly ? 'hidden' : '')">{{ $t('Dashboard') }}</span>
                    </Link>
                </li>
                <div class="px-4 py-4">
                <hr>
                </div>
                <li class="text-black hover:bg-green-50 hover:text-green-500 rounded-[5px] px-2 truncate" :class="$page.url.startsWith('/settings/whatsapp') ? 'bg-green-50 text-green-500' : ''">
                    <Link rel="noopener noreferrer" href="/settings/whatsapp" class="flex items-center p-2 space-x-3 rounded-md">
                        <svg class="text-green-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M19.05 4.91A9.816 9.816 0 0 0 12.04 2c-5.46 0-9.91 4.45-9.91 9.91c0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21c5.46 0 9.91-4.45 9.91-9.91c0-2.65-1.03-5.14-2.9-7.01m-7.01 15.24c-1.48 0-2.93-.4-4.2-1.15l-.3-.18l-3.12.82l.83-3.04l-.2-.31a8.264 8.264 0 0 1-1.26-4.38c0-4.54 3.7-8.24 8.24-8.24c2.2 0 4.27.86 5.82 2.42a8.183 8.183 0 0 1 2.41 5.83c.02 4.54-3.68 8.23-8.22 8.23m4.52-6.16c-.25-.12-1.47-.72-1.69-.81c-.23-.08-.39-.12-.56.12c-.17.25-.64.81-.78.97c-.14.17-.29.19-.54.06c-.25-.12-1.05-.39-1.99-1.23c-.74-.66-1.23-1.47-1.38-1.72c-.14-.25-.02-.38.11-.51c.11-.11.25-.29.37-.43s.17-.25.25-.41c.08-.17.04-.31-.02-.43s-.56-1.34-.76-1.84c-.2-.48-.41-.42-.56-.43h-.48c-.17 0-.43.06-.66.31c-.22.25-.86.85-.86 2.07c0 1.22.89 2.4 1.01 2.56c.12.17 1.75 2.67 4.23 3.74c.59.26 1.05.41 1.41.52c.59.19 1.13.16 1.56.1c.48-.07 1.47-.6 1.67-1.18c.21-.58.21-1.07.14-1.18s-.22-.16-.47-.28"/></svg> 
                        <span :class="menuIconsOnly ? 'hidden' : ''">{{ $t('Whatsapp Account') }}</span>
                    </Link>
                </li>
                <li class="text-black hover:bg-green-50 hover:text-green-500 rounded-[5px] px-2 truncate" :class="$page.url.startsWith('/contact') ? 'bg-green-50 text-green-500' : ''">
                    <Link rel="noopener noreferrer" href="/contacts" class="flex items-center p-2 space-x-3 rounded-md">
                        <svg class="text-green-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 4h-.8c-1.12 0-1.68 0-2.108.218a1.999 1.999 0 0 0-.874.874C4 5.52 4 6.08 4 7.2v9.6c0 1.12 0 1.68.218 2.108a2 2 0 0 0 .874.874c.427.218.987.218 2.105.218H8M8 4h8.8c1.12 0 1.68 0 2.107.218c.377.192.683.497.875.874c.218.427.218.987.218 2.105v9.607c0 1.118 0 1.677-.218 2.104a2.002 2.002 0 0 1-.875.874c-.427.218-.986.218-2.104.218H8M8 4v16m4-9h4m-4-3h4"/></svg>
                        <span :class="menuIconsOnly ? 'hidden' : ''">{{ $t('Contacts') }}</span>
                    </Link>
                </li>
                <li class="text-black hover:bg-green-50 hover:text-green-500 rounded-[5px] px-2 truncate" :class="$page.url.startsWith('/chats') ? 'bg-green-50 text-green-500' : ''">
                    <Link rel="noopener noreferrer" href="/chats" class="flex items-center justify-between p-2 space-x-3 rounded-md">
                        <div class="flex items-center space-x-3">
                            <svg class="text-green-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5.6 19.92l1.524-1.219l.01-.008c.318-.255.479-.383.658-.474c.16-.082.331-.142.508-.178c.199-.041.406-.041.822-.041h8.681c1.118 0 1.678 0 2.105-.218a2 2 0 0 0 .874-.874C21 16.48 21 15.92 21 14.804V7.197c0-1.118 0-1.678-.218-2.105a2.001 2.001 0 0 0-.875-.874C19.48 4 18.92 4 17.8 4H6.2c-1.12 0-1.68 0-2.108.218a1.999 1.999 0 0 0-.874.874C3 5.52 3 6.08 3 7.2v11.471c0 1.066 0 1.599.218 1.872a1 1 0 0 0 .783.377c.35 0 .766-.334 1.599-1Z"/></svg>
                            <span :class="menuIconsOnly ? 'hidden' : ''">{{ $t('Live Chat') }}</span>
                        </div>
                        <span v-if="parseInt(unreadMessages) > 0" class="bg-[#ffe5b4] px-2 text-[11px] rounded-md">{{ unreadMessages }}</span>
                    </Link>
                </li>
                <li class="text-black hover:bg-green-50 hover:text-green-500 rounded-[5px] px-2 truncate" :class="$page.url.startsWith('/campaign') ? 'bg-green-50 text-green-500' : ''">
                    <Link rel="noopener noreferrer" href="/campaigns" class="flex items-center p-2 space-x-3 rounded-md">
                        <svg class="text-green-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 256 256"><path fill="currentColor" d="M216 80h-32V48a16 16 0 0 0-16-16H40a16 16 0 0 0-16 16v128a8 8 0 0 0 13 6.22L72 154v30a16 16 0 0 0 16 16h93.59L219 230.22a8 8 0 0 0 5 1.78a8 8 0 0 0 8-8V96a16 16 0 0 0-16-16M66.55 137.78L40 159.25V48h128v88H71.58a8 8 0 0 0-5.03 1.78M216 207.25l-26.55-21.47a8 8 0 0 0-5-1.78H88v-32h80a16 16 0 0 0 16-16V96h32Z"/></svg>
                        <span :class="menuIconsOnly ? 'hidden' : ''">{{ $t('Broadcasting') }}</span>
                    </Link>
                </li>
                <li class="text-black hover:bg-green-50 hover:text-green-500 rounded-[5px] px-2 truncate" :class="$page.url.startsWith('/template') ? 'bg-green-50 text-green-500' : ''">
                    <Link rel="noopener noreferrer" href="/templates" class="flex items-center p-2 space-x-3 rounded-md">
                        <svg class="text-green-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 32 32"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M12 15h8m-8 4h8m8 5V11c0-1.105-.892-2-1.997-2H17c-2 0-2-3-5-3H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h20a2 2 0 0 0 2-2Z"/></svg>
                        <span :class="menuIconsOnly ? 'hidden' : ''">{{ $t('Message templates') }}</span>
                    </Link>
                </li>
                <li class="text-black hover:bg-green-50 hover:text-green-500 rounded-[5px] px-2 truncate" :class="$page.url.startsWith('/automation') ? 'bg-green-50 text-green-500' : ''">
                    <Link rel="noopener noreferrer" href="/automation/basic" class="flex items-center p-2 space-x-3 rounded-md">
                        <svg class="text-green-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 32 32"><path fill="currentColor" d="M16 27c-3.6 0-7.1-1.8-9.2-5H12v-2H4v8h2v-3.7c2.5 3 6.1 4.7 10 4.7zm15-4v-2h-2.1c-.1-.6-.4-1.2-.7-1.8l1.5-1.5l-1.4-1.4l-1.5 1.5c-.5-.3-1.1-.6-1.8-.7V15h-2v2.1c-.6.1-1.2.4-1.8.7l-1.5-1.5l-1.4 1.4l1.5 1.5c-.3.5-.6 1.1-.7 1.8H17v2h2.1c.1.6.4 1.2.7 1.8l-1.5 1.5l1.4 1.4l1.5-1.5c.5.3 1.1.6 1.8.7V29h2v-2.1c.6-.1 1.2-.4 1.8-.7l1.5 1.5l1.4-1.4l-1.5-1.5c.3-.5.6-1.1.7-1.8zm-7 2c-1.7 0-3-1.3-3-3s1.3-3 3-3s3 1.3 3 3s-1.3 3-3 3m-4-15h5.2C21.9 4.9 15.1 3.5 10 6.8c-3.1 2-5 5.5-5 9.2H3C3 8.8 8.8 3 16 3c3.9 0 7.5 1.7 10 4.7V4h2v8h-8z"/></svg>
                        <span :class="menuIconsOnly ? 'hidden' : ''">{{ $t('Chatbot Builder') }}</span>
                    </Link>
                </li>
                <li class="text-black hover:bg-green-50 hover:text-green-500 rounded-[5px] px-2 truncate" :class="$page.url.startsWith('/whatsapp/workflow') ? 'bg-green-50 text-green-500' : ''">
                    <Link rel="noopener noreferrer" href="/whatsapp/workflow" class="flex items-center p-2 space-x-3 rounded-md">
                        <svg xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="text-green-500 icon icon-tabler icons-tabler-outline icon-tabler-webhook"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4.876 13.61a4 4 0 1 0 6.124 3.39h6" /><path d="M15.066 20.502a4 4 0 1 0 1.934 -7.502c-.706 0 -1.424 .179 -2 .5l-3 -5.5" /><path d="M16 8a4 4 0 1 0 -8 0c0 1.506 .77 2.818 2 3.5l-3 5.5" /></svg>
                        <span :class="menuIconsOnly ? 'hidden' : ''">{{ $t('Webhook Workflow') }}</span>
                    </Link>
                </li>
                <li class="text-black hover:bg-green-50 hover:text-green-500 rounded-[5px] px-2 truncate" :class="$page.url.startsWith('/ecommerce') ? 'bg-green-50 text-green-500' : ''">
                    <Link rel="noopener noreferrer" href="/ecommerce/products" class="flex items-center p-2 space-x-3 rounded-md">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-500 feather feather-shopping-bag"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                        <span :class="menuIconsOnly ? 'hidden' : ''">{{ $t('eCommerce') }}</span>
                    </Link>
                </li>
                <li class="text-black hover:bg-green-50 hover:text-green-500 rounded-[5px] px-2 truncate" :class="$page.url.startsWith('/tools') ? 'bg-green-50 text-green-500' : ''">
                    <Link rel="noopener noreferrer" href="/tools" class="flex items-center p-2 space-x-3 rounded-md">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="text-green-500 bi bi-tools" viewBox="0 0 24 24">
                        <path d="M1 0 0 1l2.2 3.081a1 1 0 0 0 .815.419h.07a1 1 0 0 1 .708.293l2.675 2.675-2.617 2.654A3.003 3.003 0 0 0 0 13a3 3 0 1 0 5.878-.851l2.654-2.617.968.968-.305.914a1 1 0 0 0 .242 1.023l3.27 3.27a.997.997 0 0 0 1.414 0l1.586-1.586a.997.997 0 0 0 0-1.414l-3.27-3.27a1 1 0 0 0-1.023-.242L10.5 9.5l-.96-.96 2.68-2.643A3.005 3.005 0 0 0 16 3q0-.405-.102-.777l-2.14 2.141L12 4l-.364-1.757L13.777.102a3 3 0 0 0-3.675 3.68L7.462 6.46 4.793 3.793a1 1 0 0 1-.293-.707v-.071a1 1 0 0 0-.419-.814zm9.646 10.646a.5.5 0 0 1 .708 0l2.914 2.915a.5.5 0 0 1-.707.707l-2.915-2.914a.5.5 0 0 1 0-.708M3 11l.471.242.529.026.287.445.445.287.026.529L5 13l-.242.471-.026.529-.445.287-.287.445-.529.026L3 15l-.471-.242L2 14.732l-.287-.445L1.268 14l-.026-.529L1 13l.242-.471.026-.529.445-.287.287-.445.529-.026z"/>
                        </svg>
                        <span :class="menuIconsOnly ? 'hidden' : ''">{{ $t('Tools') }}</span>
                    </Link>
                </li>
            </ul>

            <div class="px-4 py-4">
            <hr>
            </div>
            
            <ul class="pb-4 space-y-1 text-sm mt-2">
                <li class="text-black hover:bg-green-50 hover:text-green-500 rounded-[5px] px-2 truncate" :class="$page.url.startsWith('/team') ? 'bg-green-50 text-green-500' : ''">
                    <Link rel="noopener noreferrer" href="/team" class="flex items-center p-2 space-x-3 rounded-md">
                        <svg class="text-green-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20c0-1.657-2.239-3-5-3s-5 1.343-5 3m14-3c0-1.23-1.234-2.287-3-2.75M3 17c0-1.23 1.234-2.287 3-2.75m12-4.014a3 3 0 1 0-4-4.472m-8 4.472a3 3 0 0 1 4-4.472M12 14a3 3 0 1 1 0-6a3 3 0 0 1 0 6Z"/></svg>
                        <span :class="menuIconsOnly ? 'hidden' : ''">{{ $t('Team') }}</span>
                    </Link>
                </li>
                <li class="text-black hover:bg-green-50 hover:text-green-500 rounded-[5px] px-2 truncate" :class="$page.url.startsWith('/settings') ? 'bg-green-50 text-green-500' : ''">
                    <Link rel="noopener noreferrer" href="/settings" class="md:flex items-center p-2 space-x-3 rounded-md hidden">
                        <svg class="text-green-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 16 16"><path fill="currentColor" fill-rule="evenodd" d="M3.5 2h-1v5h1zm6.1 5H6.4L6 6.45v-1L6.4 5h3.2l.4.5v1zm-5 3H1.4L1 9.5v-1l.4-.5h3.2l.4.5v1zm3.9-8h-1v2h1zm-1 6h1v6h-1zm-4 3h-1v3h1zm7.9 0h3.19l.4-.5v-.95l-.4-.5H11.4l-.4.5v.95zm2.1-9h-1v6h1zm-1 10h1v2h-1z" clip-rule="evenodd"/></svg>
                        <span :class="menuIconsOnly ? 'hidden' : ''">{{ $t('Settings') }}</span>
                    </Link>
                    <Link rel="noopener noreferrer" href="/settings/m" class="flex items-center p-2 space-x-3 rounded-md md:hidden">
                        <svg class="text-green-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 16 16"><path fill="currentColor" fill-rule="evenodd" d="M3.5 2h-1v5h1zm6.1 5H6.4L6 6.45v-1L6.4 5h3.2l.4.5v1zm-5 3H1.4L1 9.5v-1l.4-.5h3.2l.4.5v1zm3.9-8h-1v2h1zm-1 6h1v6h-1zm-4 3h-1v3h1zm7.9 0h3.19l.4-.5v-.95l-.4-.5H11.4l-.4.5v.95zm2.1-9h-1v6h1zm-1 10h1v2h-1z" clip-rule="evenodd"/></svg>
                        <span :class="menuIconsOnly ? 'hidden' : ''">{{ $t('Settings') }}</span>
                    </Link>
                </li>
                <li class="text-black hover:bg-green-50 hover:text-green-500 rounded-[5px] px-2 truncate" :class="$page.url.startsWith('/billing') || $page.url.startsWith('/subscription') ? 'bg-green-50 text-green-500' : ''">
                    <Link rel="noopener noreferrer" href="/billing" class="flex items-center p-2 space-x-3 rounded-md">
                        <svg class="text-green-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 11v4.8c0 1.12 0 1.68.218 2.108a2 2 0 0 0 .874.874c.427.218.987.218 2.105.218h11.606c1.118 0 1.677 0 2.104-.218c.377-.192.683-.498.875-.874c.218-.428.218-.986.218-2.104V11M3 11V9m0 2h18M3 9v-.8c0-1.12 0-1.68.218-2.108c.192-.377.497-.682.874-.874C4.52 5 5.08 5 6.2 5h11.6c1.12 0 1.68 0 2.107.218c.377.192.683.497.875.874c.218.427.218.987.218 2.105V9M3 9h18M7 15h4m10-4V9"/></svg>
                        <span :class="menuIconsOnly ? 'hidden' : ''">{{ $t('Billing and subscription') }}</span>
                    </Link>
                </li>
                <li class="text-black hover:bg-green-50 hover:text-green-500 rounded-[5px] px-2 truncate" :class="$page.url.startsWith('/support') ? 'bg-green-50 text-green-500' : ''">
                    <Link rel="noopener noreferrer" href="/support" class="flex items-center p-2 space-x-3 rounded-md">
                        <svg class="text-green-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" fill-rule="evenodd" d="M12.26 21.997a10.276 10.276 0 0 1-.52 0a10.004 10.004 0 0 1-8.983-6.173a10.034 10.034 0 0 1 .017-7.69A10.015 10.015 0 0 1 4.908 4.95l.042-.042a10.015 10.015 0 0 1 3.167-2.126a10.034 10.034 0 0 1 7.753-.006a10.015 10.015 0 0 1 3.186 2.138l.03.03c.913.917 1.65 2.01 2.153 3.223a10.012 10.012 0 0 1 .76 3.985a10.004 10.004 0 0 1-6.226 9.112a10.013 10.013 0 0 1-3.512.733Zm1.772-6.55l2.874 2.873a8.004 8.004 0 0 1-9.812 0l2.874-2.874a4.007 4.007 0 0 0 4.064 0Zm-5.478-1.415L5.68 16.906a8.004 8.004 0 0 1 0-9.812l2.874 2.874a4.007 4.007 0 0 0 0 4.064Zm1.528-1.463a2.01 2.01 0 0 1-.014-1.087a1.99 1.99 0 0 1 .518-.896a1.99 1.99 0 0 1 1.932-.518c.328.088.639.26.896.518a1.99 1.99 0 0 1 .518 1.932c-.088.328-.26.639-.518.896a1.99 1.99 0 0 1-1.932.518a1.991 1.991 0 0 1-.896-.518a1.99 1.99 0 0 1-.504-.845Zm3.95-4.015a4.007 4.007 0 0 0-4.064 0L7.094 5.68a8.004 8.004 0 0 1 9.812 0l-2.874 2.874Zm4.288 8.352a8.004 8.004 0 0 0 0-9.812l-2.874 2.874a4.007 4.007 0 0 1 0 4.064l2.874 2.874Z" clip-rule="evenodd"/></svg>
                        <span :class="menuIconsOnly ? 'hidden' : ''">{{ $t('Support') }}</span>
                    </Link>
                </li>
                <li v-if="user.teams[0]['role'] === 'owner'" class="text-black hover:bg-green-50 hover:text-green-500 rounded-[5px] px-2 truncate" :class="$page.url.startsWith('/developer-tools/access-tokens') ? 'bg-green-50 text-green-500' : ''">
                    <Link rel="noopener noreferrer" href="/developer-tools/access-tokens" class="flex items-center p-2 space-x-3 rounded-md">
                        <svg class="text-green-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M7.312 9H5.688L3.5 15h1.607l.446-1.226h1.894L7.893 15H9.5Zm-1.394 3.774L6.5 11.18l.582 1.595ZM14.744 9h-3.5v6h1.5v-2h2a1.473 1.473 0 0 0 1.5-1.5v-1a1.473 1.473 0 0 0-1.5-1.5m0 2.5h-2v-1h2ZM18 9h1.5v6H18z"/><path fill="currentColor" d="M22 6v12H2V6zm0-2H2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h20a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2"/></svg>
                        <span :class="menuIconsOnly ? 'hidden' : ''">{{ $t('Developer Tools') }}</span>
                    </Link>
                </li>
            </ul>
        </div>
    </div>

    <div v-if="menuIconsOnly === false" @click="switchTeams()" class="border-2 border-primary text-sm rounded-[5px] mb-1 m-3 py-2 px-4 flex items-center justify-between cursor-pointer">
        <div class="flex space-x-1">
            <span>{{ $t('Team') }}:</span>
            <span class="text-ellipsis w-[120px] truncate">{{ props.organization.name }}</span>
        </div>
        <span>
            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M19.696 8.72a1.22 1.22 0 0 1-.3.64l-6.09 6.76a1.85 1.85 0 0 1-.58.46a1.7 1.7 0 0 1-1.42.03a1.75 1.75 0 0 1-.62-.42l-6.1-6.83a1.28 1.28 0 0 1-.31-.64a1.31 1.31 0 0 1 .56-1.26a1.36 1.36 0 0 1 .68-.21h13a1.293 1.293 0 0 1 1.15.76c.081.228.092.476.03.71"></path></svg>
        </span>
    </div>
    
    <div class="flex items-center m-3 p-2 rounded-xl h-14 py-1 md:py-1 mt-2 gap-x-4 bg-slate-50" :class="!menuIconsOnly ? 'justify-between' : 'justify-center'">
        <div v-if="!menuIconsOnly" class="flex space-x-2">
            <div class="rounded-xl p-1 bg-slate-200">
                <img v-if="user.avatar" class="rounded-full w-9 h-9" :src="'/media/' + user.avatar">
                <div v-else class="rounded-full w-9 h-9 flex justify-center items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="6" r="4"/><path stroke-linecap="round" d="M19.998 18c.002-.164.002-.331.002-.5c0-2.485-3.582-4.5-8-4.5s-8 2.015-8 4.5S4 22 12 22c2.231 0 3.84-.157 5-.437"/></g></svg>
                </div>
            </div>
            <div>
                <h2 class="text-[15px] capitalize truncate w-[6em]"  :class="menuIconsOnly ? 'hidden' : ''">{{ user.first_name + ' ' + user.last_name }}</h2>
                <span class="flex items-center space-x-1" :class="menuIconsOnly ? 'hidden' : ''">
                    <span @click="openModal" class="text-sm hover:underline dark:text-gray-400 cursor-pointer">{{ $t('View profile') }}</span>
                </span>
            </div>
        </div>
        <Link href="/logout" class="hover:bg-[#F6F7F9] hover:rounded-full w-[fit-content] p-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m12 15l3-3m0 0l-3-3m3 3H4m5-4.751V7.2c0-1.12 0-1.68.218-2.108c.192-.377.497-.682.874-.874C10.52 4 11.08 4 12.2 4h4.6c1.12 0 1.68 0 2.107.218c.377.192.683.497.875.874c.218.427.218.987.218 2.105v9.607c0 1.118 0 1.677-.218 2.104a2.002 2.002 0 0 1-.875.874c-.427.218-.986.218-2.104.218h-4.606c-1.118 0-1.678 0-2.105-.218a2 2 0 0 1-.874-.874C9 18.48 9 17.92 9 16.8v-.05"/></svg>
        </Link>
    </div>

    <Modal :label="$t('Switch teams')" :isOpen="isLocationSwitchModalOpen">
        <div class="mt-2 grid grid-cols-1 gap-x-6">
            <div class="pt-3 space-y-2 text-sm">
                <div v-for="(item, index) in props.organizations" :key="index" @click="selectOrganization(item.organization.uuid)" class="flex gap-x-8 hover:bg-slate-200 rounded-lg py-1 justify-between items-center w-full cursor-pointer border border-slate-100 pl-1 pr-2">
                    <div class="flex items-center gap-x-2">
                        <span class="bg-slate-200 w-10 h-10 rounded-lg flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><g fill="none" fill-rule="evenodd"><path d="M24 0v24H0V0h24ZM12.593 23.258l-.011.002l-.071.035l-.02.004l-.014-.004l-.071-.035c-.01-.004-.019-.001-.024.005l-.004.01l-.017.428l.005.02l.01.013l.104.074l.015.004l.012-.004l.104-.074l.012-.016l.004-.017l-.017-.427c-.002-.01-.009-.017-.017-.018Zm.265-.113l-.013.002l-.185.093l-.01.01l-.003.011l.018.43l.005.012l.008.007l.201.093c.012.004.023 0 .029-.008l.004-.014l-.034-.614c-.003-.012-.01-.02-.02-.022Zm-.715.002a.023.023 0 0 0-.027.006l-.006.014l-.034.614c0 .012.007.02.017.024l.015-.002l.201-.093l.01-.008l.004-.011l.017-.43l-.003-.012l-.01-.01l-.184-.092Z"/><path fill="currentColor" d="M17 3.722v5.497l2.864.716A1.5 1.5 0 0 1 21 11.39V19a1 1 0 1 1 0 2H3a1 1 0 1 1 0-2v-7.69a1.5 1.5 0 0 1 .83-1.343L7 8.382V6.347a1.5 1.5 0 0 1 .973-1.405l7-2.625A1.5 1.5 0 0 1 17 3.722Zm-2 .721l-6 2.25V19h6V4.443Zm2 6.838V19h2v-7.22l-2-.5Zm-10-.663l-2 1V19h2v-8.382Z"/></g></svg>
                        </span>
                        <div>
                            <h3>{{ item.organization.name }}</h3>
                        </div>
                    </div>
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 20 20"><path fill="currentColor" fill-rule="evenodd" d="M5 2.643v14.765c.092.32.299.511.619.572c.32.061.633-.024.94-.255l8.107-6.993A.944.944 0 0 0 15 10a.94.94 0 0 0-.334-.73L6.58 2.295c-.232-.197-.639-.383-1.061-.253c-.282.087-.455.287-.519.6"/></svg>
                    </span>
                </div>
            </div>
            <div class="mt-4 border-t pt-4">
                <button type="button" @click.self="isLocationSwitchModalOpen = false" class="inline-flex justify-center rounded-md border border-transparent bg-slate-50 px-4 py-2 text-sm text-slate-500 hover:bg-slate-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2 mr-4">{{ $t('Cancel') }}</button>
            </div>
        </div>
    </Modal>
    <ProfileModal :user="props.user" :organization="props.organization" :isOpen="isOpen" role="user" @close="closeModal()"/>
</template>
<script setup>
    import axios from "axios"; 
    import { Link, useForm, usePage } from "@inertiajs/vue3";
    import { defineProps, ref, computed } from "vue";
    import FormInput from '@/Components/FormInput.vue';
    import Modal from '@/Components/Modal.vue';
    import ProfileModal from '@/Components/ProfileModal.vue';
    import LangToggle from '@/Components/LangToggle.vue';

    const props = defineProps(['config', 'user', 'organization', 'organizations', 'isSidebarOpen', 'menuIconsOnly', 'unreadMessages']);

    const languages = computed(() => usePage().props.languages);
    const currentLanguage = computed(() => usePage().props.currentLanguage);
    const isOpen = ref(false);
    const isLocationSwitchModalOpen = ref(false);
    const showDropdown1 = ref(false);

    const emit = defineEmits(['closeSidebar']);

    const form = useForm({
        uuid: null,
    })

    const getValueByKey = (key) => {
        const found = props.config.find(item => item.key === key);
        return found ? found.value : '';
    };

    const closeSidebar = () => {
        emit('closeSidebar', true);
    }

    const toggleDropdown = (type) => {
        if(type === 'dropdown1'){
            showDropdown1.value = !showDropdown1.value;
        }
    }

    const closeModal = () => {
        isOpen.value = false
    }

    const openModal = () => {
        isOpen.value = true;
        emit('closeSidebar', true);
    }

    const switchTeams = () => {
        isLocationSwitchModalOpen.value = true; 
        emit('closeSidebar', true);
    }

    const selectOrganization = (uuid) => {
        form.uuid = uuid;
        submitForm();
    }

    const submitForm = async () => {
        form.post('/organization', {
            preserveScroll: true,
            onFinish: isLocationSwitchModalOpen.value = false,
        })
    };
</script>