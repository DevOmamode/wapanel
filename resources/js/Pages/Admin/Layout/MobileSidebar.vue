<template>
    <div class="fixed top-0 z-10 w-full bg-white border-b px-4 py-4 flex items-center justify-between md:hidden">
        <div>
            <span @click="isSidebarOpen = true">
                <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 17h8m-8-5h14M5 7h8"/></svg>
            </span>
        </div>
        <h3 class="text-xl">{{ props.title }}</h3>
        <div>
            <Link v-if="props.displayCreateBtn" :href="$page.url + '/create'">
                <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 20 20"><path fill="currentColor" d="M10 6.5a4.5 4.5 0 1 1-9 0a4.5 4.5 0 0 1 9 0m-4-2a.5.5 0 0 0-1 0V6H3.5a.5.5 0 0 0 0 1H5v1.5a.5.5 0 0 0 1 0V7h1.5a.5.5 0 0 0 0-1H6zm6 .5h-1.207a5.466 5.466 0 0 0-.393-1H12a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-1.257c.307.253.642.474 1 .657v.6a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V6a1 1 0 0 0-1-1M6 16a2 2 0 0 1-1.732-1H12a3 3 0 0 0 3-3V6.268A2 2 0 0 1 16 8v4a4 4 0 0 1-4 4zm2 2a2 2 0 0 1-1.732-1H12a5 5 0 0 0 5-5V8.268A2 2 0 0 1 18 10v2a6 6 0 0 1-6 6z"/></svg>
            </Link>
            <span v-else  @click="openModal">
                <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"><path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10s10-4.477 10-10S17.523 2 12 2"/><path d="M4.271 18.346S6.5 15.5 12 15.5s7.73 2.846 7.73 2.846M12 12a3 3 0 1 0 0-6a3 3 0 0 0 0 6"/></g></svg>
            </span>
        </div>
    </div>
    <aside
        class="transform top-0 left-0 w-full bg-white fixed h-full overflow-auto ease-in-out transition-all duration-300 z-30"
        :class="isSidebarOpen ? 'translate-x-0' : '-translate-x-full'">
        <aside class="flex flex-col h-full w-full bg-white">
            <div v-if="getValueByKey('logo') === null" class="flex items-center justify-between px-5 pt-5 h-20 mb-4">
                <h1 class="ml-2 text-3xl">{{ getValueByKey('company_name') }}</h1>
                <span v-if="isSidebarOpen === true" @click="isSidebarOpen = false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="2.5em" height="2.5em" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="2"><path d="M5 5L12 5L19 5"><animate fill="freeze" attributeName="d" dur="0.4s" values="M5 5L12 5L19 5;M5 5L12 12L19 5"/></path><path d="M5 12H19"><animate fill="freeze" attributeName="d" dur="0.4s" values="M5 12H19;M12 12H12"/></path><path d="M5 19L12 19L19 19"><animate fill="freeze" attributeName="d" dur="0.4s" values="M5 19L12 19L19 19;M5 19L12 12L19 19"/></path></g></svg>
                </span>
            </div>
            <Link href="/admin/dashboard" v-else class="w-full flex items-center justify-between px-5 pt-5 w-48 h-20 mb-1">
                <img :src="'/media/' + getValueByKey('logo')" alt="{{ getValueByKey('company_name') }}" class="object-contain w-48 ps-2">
                <span v-if="isSidebarOpen === true" @click="isSidebarOpen = false" class="float-right">
                    <svg xmlns="http://www.w3.org/2000/svg" width="2.5em" height="2.5em" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="2"><path d="M5 5L12 5L19 5"><animate fill="freeze" attributeName="d" dur="0.4s" values="M5 5L12 5L19 5;M5 5L12 12L19 5"/></path><path d="M5 12H19"><animate fill="freeze" attributeName="d" dur="0.4s" values="M5 12H19;M12 12H12"/></path><path d="M5 19L12 19L19 19"><animate fill="freeze" attributeName="d" dur="0.4s" values="M5 19L12 19L19 19;M5 19L12 12L19 19"/></path></g></svg>
                </span>
            </Link>
            <div class="flex-grow space-y-3 px-2 overflow-y-scroll">
                <div class="flex-1">
                    <ul class="pt-2 space-y-3 pb-4">
                        <li class="hover:bg-slate-50 hover:text-black rounded-[5px] px-2" :class="$page.url.startsWith('/admin/dashboard') ? 'bg-slate-50 text-black' : ''">
                            <Link rel="noopener noreferrer" href="/admin/dashboard" class="flex items-center p-2 space-x-3 rounded-md">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 11.452V16.8c0 1.12 0 1.68.218 2.109c.192.376.497.682.874.873c.427.218.987.218 2.105.218h9.606c1.118 0 1.677 0 2.104-.218a2 2 0 0 0 .875-.873c.218-.428.218-.987.218-2.105v-5.352c0-.534 0-.801-.065-1.05a1.998 1.998 0 0 0-.28-.617c-.145-.213-.345-.39-.748-.741l-4.8-4.2c-.746-.653-1.12-.98-1.54-1.104c-.37-.11-.764-.11-1.135 0c-.42.124-.792.45-1.538 1.102L5.093 9.044c-.402.352-.603.528-.747.74a2 2 0 0 0-.281.618C4 10.65 4 10.918 4 11.452Z"/></svg>
                                <span>{{ $t('Dashboard') }}</span>
                            </Link>
                        </li>
                        <li class="hover:bg-slate-50 hover:text-black rounded-[5px] px-2" :class="$page.url.startsWith('/admin/organization') ? 'bg-slate-50 text-black' : ''">
                            <Link rel="noopener noreferrer" href="/admin/organizations" class="flex items-center p-2 space-x-3 rounded-md">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><g fill="none" fill-rule="evenodd"><path d="M24 0v24H0V0h24ZM12.593 23.258l-.011.002l-.071.035l-.02.004l-.014-.004l-.071-.035c-.01-.004-.019-.001-.024.005l-.004.01l-.017.428l.005.02l.01.013l.104.074l.015.004l.012-.004l.104-.074l.012-.016l.004-.017l-.017-.427c-.002-.01-.009-.017-.017-.018Zm.265-.113l-.013.002l-.185.093l-.01.01l-.003.011l.018.43l.005.012l.008.007l.201.093c.012.004.023 0 .029-.008l.004-.014l-.034-.614c-.003-.012-.01-.02-.02-.022Zm-.715.002a.023.023 0 0 0-.027.006l-.006.014l-.034.614c0 .012.007.02.017.024l.015-.002l.201-.093l.01-.008l.004-.011l.017-.43l-.003-.012l-.01-.01l-.184-.092Z"/><path fill="currentColor" d="M17 3.722v5.497l2.864.716A1.5 1.5 0 0 1 21 11.39V19a1 1 0 1 1 0 2H3a1 1 0 1 1 0-2v-7.69a1.5 1.5 0 0 1 .83-1.343L7 8.382V6.347a1.5 1.5 0 0 1 .973-1.405l7-2.625A1.5 1.5 0 0 1 17 3.722Zm-2 .721l-6 2.25V19h6V4.443Zm2 6.838V19h2v-7.22l-2-.5Zm-10-.663l-2 1V19h2v-8.382Z"/></g></svg>
                                <span>{{ $t('Organizations') }}</span>
                            </Link>
                        </li>
                        <li class="hover:bg-slate-50 hover:text-black rounded-[5px] px-2" :class="$page.url.startsWith('/admin/user') ? 'bg-slate-50 text-black' : ''">
                            <Link rel="noopener noreferrer" href="/admin/users" class="flex items-center p-2 space-x-3 rounded-md">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20c0-1.657-2.239-3-5-3s-5 1.343-5 3m14-3c0-1.23-1.234-2.287-3-2.75M3 17c0-1.23 1.234-2.287 3-2.75m12-4.014a3 3 0 1 0-4-4.472m-8 4.472a3 3 0 0 1 4-4.472M12 14a3 3 0 1 1 0-6a3 3 0 0 1 0 6Z"/></svg>
                                <span>{{ $t('Users') }}</span>
                            </Link>
                        </li>
                        <li class="hover:bg-slate-50 hover:text-black rounded-[5px] px-2" :class="$page.url.startsWith('/admin/payment-logs') ? 'bg-slate-50 text-black' : ''">
                            <Link rel="noopener noreferrer" href="/admin/payment-logs" class="flex items-center p-2 space-x-3 rounded-md">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 11v4.8c0 1.12 0 1.68.218 2.108a2 2 0 0 0 .874.874c.427.218.987.218 2.105.218h11.606c1.118 0 1.677 0 2.104-.218c.377-.192.683-.498.875-.874c.218-.428.218-.986.218-2.104V11M3 11V9m0 2h18M3 9v-.8c0-1.12 0-1.68.218-2.108c.192-.377.497-.682.874-.874C4.52 5 5.08 5 6.2 5h11.6c1.12 0 1.68 0 2.107.218c.377.192.683.497.875.874c.218.427.218.987.218 2.105V9M3 9h18M7 15h4m10-4V9"/></svg>
                                <span>{{ $t('Billing') }}</span>
                            </Link>
                        </li>
                        <li v-if="props.user.email == masterAdminEmail" class="hover:bg-slate-50 hover:text-black rounded-[5px] px-2" :class="$page.url.startsWith('/admin/support') ? 'bg-slate-50 text-black' : ''">
                            <Link rel="noopener noreferrer" href="/admin/support" class="flex items-center p-2 space-x-3 rounded-md">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8h4a1 1 0 0 1 1 1v11l-3.333-2.769a1.002 1.002 0 0 0-.64-.231H9a1 1 0 0 1-1-1v-3m8-5V5a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v11l3.333-2.77c.18-.148.406-.23.64-.23H8m8-5v4a1 1 0 0 1-1 1H8"/></svg>
                                <span>{{ $t('Support desk') }}</span>
                            </Link>
                        </li>
                        <li v-if="props.user.email == masterAdminEmail" class="hover:bg-slate-50 hover:text-black rounded-[5px] px-2" :class="$page.url.startsWith('/admin/team/users') ? 'bg-slate-50 text-black' : ''">
                            <Link rel="noopener noreferrer" href="/admin/team/users" class="flex items-center p-2 space-x-3 rounded-md">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 20c0-1.742-1.67-3.223-4-3.773M15 20c0-2.21-2.686-4-6-4s-6 1.79-6 4m12-7a4 4 0 0 0 0-8m-6 8a4 4 0 1 1 0-8a4 4 0 0 1 0 8Z"/></svg>
                                <span>{{ $t('Team') }}</span>
                            </Link>
                        </li>
                        <!--<li class="rounded-[5px]">
                            <span @click="toggleDropdown('dropdown3')" class="hover:bg-slate-50 hover:text-black cursor-pointer flex items-center justify-between p-2 rounded-md w-full px-4">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 4h-.8c-1.12 0-1.68 0-2.108.218a1.999 1.999 0 0 0-.874.874C4 5.52 4 6.08 4 7.2v9.6c0 1.12 0 1.68.218 2.108a2 2 0 0 0 .874.874c.427.218.987.218 2.105.218H8M8 4h8.8c1.12 0 1.68 0 2.107.218c.377.192.683.497.875.874c.218.427.218.987.218 2.105v9.607c0 1.118 0 1.677-.218 2.104a2.002 2.002 0 0 1-.875.874c-.427.218-.986.218-2.104.218H8M8 4v16m4-9h4m-4-3h4"/></svg>
                                    <span class="ml-3">Blog Posts</span>
                                </div>
                                <span>
                                    <svg v-if="!showDropdown3" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m11 9l3 3l-3 3"/></svg>
                                    <svg v-else xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 11l-3 3l-3-3"/></svg>
                                </span>
                            </span>
                            <ul v-if="showDropdown3" class="ml-7 pl-2 border-l-2 border-[#ffffffcc]">
                                <li class="rounded-[5px] hover:bg-slate-50 hover:text-black pl-2 mr-4">
                                    <Link href="/admin/blog/posts" class="block w-full p-2">Posts</Link>
                                </li>
                                <li class="rounded-[5px] hover:bg-slate-50 hover:text-black pl-2 mr-4">
                                    <Link href="/admin/blog/categories" class="block w-full p-2">Categories</Link>
                                </li>
                                <li class="rounded-[5px] hover:bg-slate-50 hover:text-black pl-2 mr-4">
                                    <Link href="/admin/blog/tags" class="block w-full p-2">Tags</Link>
                                </li>
                                <li class="rounded-[5px] hover:bg-slate-50 hover:text-black pl-2 mr-4">
                                    <Link href="/admin/blog/authors" class="block w-full p-2">Authors</Link>
                                </li>
                            </ul>
                        </li>-->
                    </ul>

                    <div class="px-4">
                        <hr>
                    </div>
                    
                    <ul class="pb-4 space-y-3 mt-2 pt-2">
                        <li v-if="props.user.email == masterAdminEmail" class="hover:bg-slate-50 hover:text-black rounded-[5px] px-2" :class="$page.url.startsWith('/admin/team/roles') ? 'bg-slate-50 text-black' : ''">
                            <Link rel="noopener noreferrer" href="/admin/team/roles" class="flex items-center p-2 space-x-3 rounded-md">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 20c0-1.742-1.67-3.223-4-3.773M15 20c0-2.21-2.686-4-6-4s-6 1.79-6 4m12-7a4 4 0 0 0 0-8m-6 8a4 4 0 1 1 0-8a4 4 0 0 1 0 8Z"/></svg>
                                <span>{{ $t('Roles') }}</span>
                            </Link>
                        </li>
                        
                        <li class="hover:bg-slate-50 hover:text-black rounded-[5px] px-2" :class="$page.url.startsWith('/admin/plan') ? 'bg-slate-50 text-black' : ''">
                            <Link rel="noopener noreferrer" href="/admin/plans" class="flex items-center p-2 space-x-3 rounded-md">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 11v4.8c0 1.12 0 1.68.218 2.108a2 2 0 0 0 .874.874c.427.218.987.218 2.105.218h11.606c1.118 0 1.677 0 2.104-.218c.377-.192.683-.498.875-.874c.218-.428.218-.986.218-2.104V11M3 11V9m0 2h18M3 9v-.8c0-1.12 0-1.68.218-2.108c.192-.377.497-.682.874-.874C4.52 5 5.08 5 6.2 5h11.6c1.12 0 1.68 0 2.107.218c.377.192.683.497.875.874c.218.427.218.987.218 2.105V9M3 9h18M7 15h4m10-4V9"/></svg>
                                <span>{{ $t('Subscription plans') }}</span>
                            </Link>
                        </li>
                        <li v-if="props.user.email == masterAdminEmail" class="hover:bg-slate-50 hover:text-black rounded-[5px] px-2" :class="$page.url.startsWith('/admin/faq') ? 'bg-slate-50 text-black' : ''">
                            <Link rel="noopener noreferrer" href="/admin/faqs" class="flex items-center p-2 space-x-3 rounded-md">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M5.455 15L1 18.5V3a1 1 0 0 1 1-1h15a1 1 0 0 1 1 1v12zm-.692-2H16V4H3v10.385zM8 17h10.237L20 18.385V8h1a1 1 0 0 1 1 1v13.5L17.546 19H9a1 1 0 0 1-1-1z"/></svg>
                                <span>{{ $t('FAQs') }}</span>
                            </Link>
                        </li>
                        <li v-if="props.user.email == masterAdminEmail" class="hover:bg-slate-50 hover:text-black rounded-[5px] px-2" :class="$page.url.startsWith('/admin/testimonial') ? 'bg-slate-50 text-black' : ''">
                            <Link rel="noopener noreferrer" href="/admin/testimonials" class="flex items-center p-2 space-x-3 rounded-md">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"><path d="M20 12V5.749a.6.6 0 0 0-.176-.425l-3.148-3.148A.6.6 0 0 0 16.252 2H4.6a.6.6 0 0 0-.6.6v18.8a.6.6 0 0 0 .6.6H11M8 10h8M8 6h4m-4 8h3"/><path d="m16.306 17.113l.909-1.927a.312.312 0 0 1 .57 0l.91 1.927l2.032.311c.261.04.365.376.177.568l-1.471 1.5l.347 2.118c.044.272-.229.48-.462.351l-1.818-1l-1.818 1c-.234.129-.506-.079-.462-.351l.347-2.118l-1.47-1.5c-.19-.192-.085-.528.176-.568zM16 2v3.4a.6.6 0 0 0 .6.6H20"/></g></svg>
                                <span>{{ $t('Reviews') }}</span>
                            </Link>
                        </li>
                        <li v-if="props.user.email == masterAdminEmail" class="hover:bg-slate-50 hover:text-black rounded-[5px] px-2" :class="$page.url.startsWith('/admin/setting') ? 'bg-slate-50 text-black' : ''">
                            <Link rel="noopener noreferrer" href="/admin/settings" class="flex items-center p-2 space-x-3 rounded-md">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="m20.35 8.923l-.366-.204a2 2 0 0 1-.784-.724c-.017-.027-.033-.056-.065-.112a2.002 2.002 0 0 1-.3-1.157l.006-.425c.012-.68.018-1.022-.078-1.328a1.998 1.998 0 0 0-.417-.736c-.214-.24-.511-.412-1.106-.754l-.494-.285c-.592-.341-.889-.512-1.204-.577a1.999 1.999 0 0 0-.843.007c-.313.07-.606.246-1.191.596l-.003.002l-.354.211c-.056.034-.085.05-.113.066c-.278.155-.588.24-.907.25c-.032.002-.065.002-.13.002l-.13-.001a1.997 1.997 0 0 1-.91-.252c-.028-.015-.055-.032-.111-.066l-.357-.214c-.589-.354-.884-.53-1.199-.601a1.998 1.998 0 0 0-.846-.006c-.316.066-.612.238-1.205.582l-.003.001l-.488.283l-.005.004c-.588.34-.883.512-1.095.751a2 2 0 0 0-.415.734c-.095.307-.09.649-.078 1.333l.007.424c0 .065.003.097.002.128a2.002 2.002 0 0 1-.301 1.027c-.033.056-.048.084-.065.11a2 2 0 0 1-.675.664l-.112.063l-.361.2c-.602.333-.903.5-1.121.738a2 2 0 0 0-.43.73c-.1.307-.1.65-.099 1.338l.002.563c.001.683.003 1.024.104 1.329a2 2 0 0 0 .427.726c.218.236.516.402 1.113.734l.358.199c.061.034.092.05.121.068a2 2 0 0 1 .74.781l.067.12a2 2 0 0 1 .23 1.038l-.007.407c-.012.686-.017 1.03.079 1.337c.085.272.227.523.417.736c.214.24.512.411 1.106.754l.494.285c.593.341.889.512 1.204.577a2 2 0 0 0 .843-.007c.314-.07.607-.246 1.194-.598l.354-.212a1.997 1.997 0 0 1 1.02-.317h.26c.318.01.63.097.91.252l.092.055l.376.226c.59.354.884.53 1.199.6a2 2 0 0 0 .846.008c.315-.066.613-.239 1.206-.583l.495-.287c.588-.342.883-.513 1.095-.752c.19-.213.33-.463.415-.734c.095-.305.09-.644.078-1.318l-.008-.44a2 2 0 0 1 .3-1.155l.065-.11a2 2 0 0 1 .675-.664l.11-.061l.002-.001l.361-.2c.602-.334.903-.5 1.122-.738c.194-.21.34-.46.429-.73c.1-.305.1-.647.098-1.327l-.002-.574c-.001-.683-.002-1.025-.103-1.33a2.002 2.002 0 0 0-.428-.725c-.217-.236-.515-.402-1.111-.733l-.002-.001Z"/><path d="M8 12a4 4 0 1 0 8 0a4 4 0 0 0-8 0Z"/></g></svg>
                                <span class="ml-3">{{ $t('Settings') }}</span>
                            </Link>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="flex items-center m-3 p-2 rounded-[5px] h-20 py-1 md:py-1 mt-2 space-x-4 justify-between bg-slate-50">
                <div class="flex space-x-2">
                    <div class="rounded-full p-1">
                        <img v-if="user.avatar" class="rounded-full w-9 h-9" :src="'/media/' + user.avatar">
                        <div v-else class="rounded-full w-10 h-10 flex justify-center items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="6" r="4"/><path stroke-linecap="round" d="M19.998 18c.002-.164.002-.331.002-.5c0-2.485-3.582-4.5-8-4.5s-8 2.015-8 4.5S4 22 12 22c2.231 0 3.84-.157 5-.437"/></g></svg>
                        </div>
                    </div>
                    <div>
                        <h2 class="text-[18px] capitalize truncate w-[6em]">{{ user.first_name + ' ' + user.last_name }}</h2>
                        <span class="flex items-center space-x-1">
                            <span @click="openModal" class="text-sm hover:underline dark:text-gray-400 cursor-pointer">{{ $t('View profile') }}</span>
                        </span>
                    </div>
                </div>
                <Link href="/logout" class="hover:bg-[#F6F7F9] hover:rounded-full w-[fit-content] p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m12 15l3-3m0 0l-3-3m3 3H4m5-4.751V7.2c0-1.12 0-1.68.218-2.108c.192-.377.497-.682.874-.874C10.52 4 11.08 4 12.2 4h4.6c1.12 0 1.68 0 2.107.218c.377.192.683.497.875.874c.218.427.218.987.218 2.105v9.607c0 1.118 0 1.677-.218 2.104a2.002 2.002 0 0 1-.875.874c-.427.218-.986.218-2.104.218h-4.606c-1.118 0-1.678 0-2.105-.218a2 2 0 0 1-.874-.874C9 18.48 9 17.92 9 16.8v-.05"/></svg>
                </Link>
            </div>
        </aside>
    </aside>
    <ProfileModal :user="props.user" :organization="{}" :isOpen="isOpen" role="admin" @close="closeModal()"/>
</template>
<script setup>
    import axios from "axios"; 
    import { Link, useForm } from "@inertiajs/vue3";
    import { defineProps, ref } from "vue";
    import FormInput from '@/Components/FormInput.vue';
    import ProfileModal from '@/Components/ProfileModal.vue';
    const masterAdminEmail = import.meta.env.VITE_MASTER_ADMIN_MAIL;

    const props = defineProps({
        title: {
            type: String,
        },
        displayCreateBtn: {
            type: String,
        },
        user: {
            type: Object,
            required: true,
        },
        config: {
            type: Array,
            required: true
        }
    });

    const isSidebarOpen = ref(false);
    const isLoading = ref(false);
    const isOpen = ref(false);

    const getValueByKey = (key) => {
        const found = props.config.find(item => item.key === key);
        return found ? found.value : '';
    };
    
    const closeModal = () => {
        isOpen.value = false
    }

    const openModal = () => {
        isOpen.value = true;
        isSidebarOpen.value = false;
    }
</script>