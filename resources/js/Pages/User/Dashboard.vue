<template>
    <AppLayout>
        <div class="p-4 pb-0 md:p-8 md:pb-0 rounded-[5px] text-[#000] h-full overflow-y-auto scrollbar-thin capitalize">
            <div class="flex justify-between mt-3 md:mt-0">
                <div>
                    <h2 class="md:block hidden text-xl mb-1">{{ $t('Dashboard') }}</h2>
                    <p class="mb-6 flex items-center leading-6">
                        <span class="mt-1 font-semibold md:font-normal text-xl">{{ $t('Welcome back') }}, {{ props.auth.user.first_name }} 👋</span>
                    </p>
                </div>
            </div>
            <div class="flex space-x-2 mb-8 text-xs md:text-sm">
                <Link href="/contacts/add" class="bg-primary py-2 px-3 rounded-lg text-white">{{ $t('Add contact') }}</Link>
                <Link href="/campaigns/create" class="bg-primary py-2 px-3 rounded-lg text-white">{{ $t('Create campaign') }}</Link>
                <Link href="/templates/create" class="bg-primary py-2 px-3 rounded-lg text-white">{{ $t('Create template') }}</Link>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-x-4 gap-y-4 md:space-y-0">
                <div class="bg-slate-100 md:bg-white col-span-2 md:col-span-1 rounded-lg p-3">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2>{{ $t('Contacts') }}</h2>
                            <h2 class="text-xl text-gray-600">{{ props.contactCount }}</h2>
                        </div>
                        <div class="flex">
                            <span class="bg-secondary/10 p-3 rounded-full self-start">
                                <svg class="text-secondary" xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="1.5"><path d="M2 12c0-4.714 0-7.071 1.464-8.536C4.93 2 7.286 2 12 2c4.714 0 7.071 0 8.535 1.464C22 4.93 22 7.286 22 12c0 4.714 0 7.071-1.465 8.535C19.072 22 16.714 22 12 22s-7.071 0-8.536-1.465C2 19.072 2 16.714 2 12Z"/><path stroke-linecap="round" stroke-linejoin="round" d="m7 14l2.293-2.293a1 1 0 0 1 1.414 0l1.586 1.586a1 1 0 0 0 1.414 0L17 10m0 0v2.5m0-2.5h-2.5"/></g></svg>
                            </span>
                        </div>
                    </div>
                    <div class="text-sm space-x-1 md:block hidden">
                        <Link href="/contacts" class="flex items-center space-x-1 underline">
                            <span>{{ $t('View contacts') }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 16l8-8m0 0h-6m6 0v6"></path></svg>
                        </Link>
                    </div>
                </div>
                <div class="bg-slate-100 md:bg-white col-span-1 rounded-lg p-3">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2>{{ $t('Campaigns') }}</h2>
                            <h2 class="text-xl text-gray-600">{{ props.campaignCount }}</h2>
                        </div>
                        <div class="flex">
                            <span class="bg-secondary/10 p-3 rounded-full self-start">
                                <svg class="text-secondary" xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="1.5"><path d="M2 12c0-4.714 0-7.071 1.464-8.536C4.93 2 7.286 2 12 2c4.714 0 7.071 0 8.535 1.464C22 4.93 22 7.286 22 12c0 4.714 0 7.071-1.465 8.535C19.072 22 16.714 22 12 22s-7.071 0-8.536-1.465C2 19.072 2 16.714 2 12Z"/><path stroke-linecap="round" stroke-linejoin="round" d="m7 14l2.293-2.293a1 1 0 0 1 1.414 0l1.586 1.586a1 1 0 0 0 1.414 0L17 10m0 0v2.5m0-2.5h-2.5"/></g></svg>
                            </span>
                        </div>
                    </div>
                    <div class="text-sm space-x-1 md:block hidden">
                        <Link href="/campaigns" class="flex items-center space-x-1 underline">
                            <span>{{ $t('View campaigns') }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 16l8-8m0 0h-6m6 0v6"></path></svg>
                        </Link>
                    </div>
                </div>
                <div class="bg-slate-100 md:bg-white rounded-lg p-3">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2>{{ $t('Templates') }}</h2>
                            <h2 class="text-xl text-gray-600">{{ props.templateCount }}</h2>
                        </div>
                        <div class="flex">
                            <span class="bg-secondary/10 p-3 rounded-full self-start">
                                <svg class="text-secondary" xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="1.5"><path d="M2 12c0-4.714 0-7.071 1.464-8.536C4.93 2 7.286 2 12 2c4.714 0 7.071 0 8.535 1.464C22 4.93 22 7.286 22 12c0 4.714 0 7.071-1.465 8.535C19.072 22 16.714 22 12 22s-7.071 0-8.536-1.465C2 19.072 2 16.714 2 12Z"/><path stroke-linecap="round" stroke-linejoin="round" d="m7 14l2.293-2.293a1 1 0 0 1 1.414 0l1.586 1.586a1 1 0 0 0 1.414 0L17 10m0 0v2.5m0-2.5h-2.5"/></g></svg>
                            </span>
                        </div>
                    </div>
                    <div class="text-sm space-x-1 md:block hidden">
                        <Link href="/templates" class="flex items-center space-x-1 underline">
                            <span>{{ $t('View templates') }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 16l8-8m0 0h-6m6 0v6"></path></svg>
                        </Link>
                    </div>
                </div>
                <div class="bg-slate-100 col-span-2 md:col-span-1 md:bg-white rounded-lg p-3">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2>{{ $t('All chats') }}</h2>
                            <h2 class="text-xl text-gray-600">{{ props.chatCount }}</h2>
                        </div>
                        <div class="flex">
                            <span class="bg-secondary/10 p-3 rounded-full self-start">
                                <svg class="text-secondary" xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="1.5"><path d="M2 12c0-4.714 0-7.071 1.464-8.536C4.93 2 7.286 2 12 2c4.714 0 7.071 0 8.535 1.464C22 4.93 22 7.286 22 12c0 4.714 0 7.071-1.465 8.535C19.072 22 16.714 22 12 22s-7.071 0-8.536-1.465C2 19.072 2 16.714 2 12Z"/><path stroke-linecap="round" stroke-linejoin="round" d="m7 14l2.293-2.293a1 1 0 0 1 1.414 0l1.586 1.586a1 1 0 0 0 1.414 0L17 10m0 0v2.5m0-2.5h-2.5"/></g></svg>
                            </span>
                        </div>
                    </div>
                    <div class="text-sm space-x-1 md:block hidden">
                        <Link href="/contacts" class="flex items-center space-x-1 underline">
                            <span>{{ $t('View chats') }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 16l8-8m0 0h-6m6 0v6"></path></svg>
                        </Link>
                    </div>
                </div>
            </div>
            <div class="md:grid md:grid-cols-2 gap-x-4 mt-4">
                <div class="md:block hidden">
                    <div id="chart">
                        <apexchart type="area" height="350" :options="chartOptions" :series="series"></apexchart>
                    </div>
                </div>
                <div class="space-y-5">
                    <div class="flex justify-between rounded-lg py-4 px-4" :class="!subscriptionIsActive ? 'bg-red-500 text-white' : 'bg-slate-100 md:bg-white'">
                        <div>
                            <h2 v-if="props.subscription?.status === 'trial' && !subscriptionIsActive">{{ $t('Your trial period is over') }}</h2>
                            <h2 v-if="props.subscription?.status === 'active' && !subscriptionIsActive">{{ $t('We were unable to autorenew your subscription') }}</h2>
                            <h2 v-if="props.subscription?.status === 'active' && subscriptionIsActive">{{ $t('Active subscription') }}</h2>
                            <h2 v-if="props.subscription?.status === 'trial' && subscriptionIsActive">{{ $t('Trial period') }}</h2>

                            <p v-if="props.subscription?.status === 'trial' && !subscriptionIsActive" class="text-sm">{{ $t('Please subscribe to a plan to continue using the app') }}</p>
                            <p v-if="props.subscription?.status === 'active' && !subscriptionIsActive" class="text-sm">{{ $t('To continue using the app, please make a payment of') }} {{ props.subscriptionDetails?.accountBalance }}.</p>
                            <p v-if="props.subscription?.status === 'trial' && subscriptionIsActive" class="text-sm">{{ $t('Your trial period expires on') }} {{ props.subscription?.valid_until }}</p>
                            <p v-if="props.subscription?.status === 'active' && subscriptionIsActive" class="text-sm">{{ $t('Your subscription expires on') }} {{ props.subscription?.valid_until }}</p>

                            <Link href="/subscription" v-if="props.auth.user.teams[0].role === 'owner' && props.subscription?.status === 'trial'" class="p-2 rounded-lg text-sm mt-5 flex px-3 w-fit" :class="!subscriptionIsActive ? 'bg-white text-gray-600' : 'bg-primary text-white'">
                                {{ $t('Subscribe') }}
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><g fill="currentColor" fill-rule="evenodd" clip-rule="evenodd"><g opacity=".2"><path d="M12.206 5.848a1.5 1.5 0 0 1 2.113.192l3.333 4a1.5 1.5 0 1 1-2.304 1.92l-3.334-4a1.5 1.5 0 0 1 .192-2.112Z"/><path d="M12.206 16.152a1.5 1.5 0 0 1-.192-2.112l3.334-4a1.5 1.5 0 0 1 2.304 1.92l-3.333 4a1.5 1.5 0 0 1-2.113.192Z"/><path d="M16 11a1.5 1.5 0 0 1-1.5 1.5h-8a1.5 1.5 0 0 1 0-3h8A1.5 1.5 0 0 1 16 11Z"/></g><path d="M11.347 5.616a.5.5 0 0 1 .704.064l3.333 4a.5.5 0 0 1-.768.64l-3.333-4a.5.5 0 0 1 .064-.704Z"/><path d="M11.347 14.384a.5.5 0 0 1-.064-.704l3.333-4a.5.5 0 0 1 .768.64l-3.333 4a.5.5 0 0 1-.704.064Z"/><path d="M15.5 10a.5.5 0 0 1-.5.5H5a.5.5 0 0 1 0-1h20a.5.5 0 0 1 .5.5Z"/></g></svg>
                            </Link>
                            <Link href="/billing" v-if="props.auth.user.teams[0].role === 'owner' && props.subscription?.status === 'active' && !subscriptionIsActive" type="button" class="bg-white text-gray-600 p-2 rounded-lg text-sm mt-5 flex px-3 w-fit">
                                {{ $t('Add payment') }}
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><g fill="currentColor" fill-rule="evenodd" clip-rule="evenodd"><g opacity=".2"><path d="M12.206 5.848a1.5 1.5 0 0 1 2.113.192l3.333 4a1.5 1.5 0 1 1-2.304 1.92l-3.334-4a1.5 1.5 0 0 1 .192-2.112Z"/><path d="M12.206 16.152a1.5 1.5 0 0 1-.192-2.112l3.334-4a1.5 1.5 0 0 1 2.304 1.92l-3.333 4a1.5 1.5 0 0 1-2.113.192Z"/><path d="M16 11a1.5 1.5 0 0 1-1.5 1.5h-8a1.5 1.5 0 0 1 0-3h8A1.5 1.5 0 0 1 16 11Z"/></g><path d="M11.347 5.616a.5.5 0 0 1 .704.064l3.333 4a.5.5 0 0 1-.768.64l-3.333-4a.5.5 0 0 1 .064-.704Z"/><path d="M11.347 14.384a.5.5 0 0 1-.064-.704l3.333-4a.5.5 0 0 1 .768.64l-3.333 4a.5.5 0 0 1-.704.064Z"/><path d="M15.5 10a.5.5 0 0 1-.5.5H5a.5.5 0 0 1 0-1h20a.5.5 0 0 1 .5.5Z"/></g></svg>
                            </Link>
                        </div>
                        <div class="flex">
                            <span class=" h-16 w-16 p-3 flex justify-center items-center rounded-full self-start" :class="!subscriptionIsActive ? 'bg-white text-red-500' : 'bg-primary text-white'">
                                <svg v-if="subscriptionIsActive" xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24"><g fill="none"><path stroke="currentColor" stroke-linecap="round" stroke-width="1.5" d="M14 22h-4c-3.771 0-5.657 0-6.828-1.172C2 19.657 2 17.771 2 14v-2c0-3.771 0-5.657 1.172-6.828C4.343 4 6.229 4 10 4h4c3.771 0 5.657 0 6.828 1.172C22 6.343 22 8.229 22 12v2c0 3.771 0 5.657-1.172 6.828c-.653.654-1.528.943-2.828 1.07M7 4V2.5M17 4V2.5M21.5 9H20.75M2 9h3.875"/><path fill="currentColor" d="M18 17a1 1 0 1 1-2 0a1 1 0 0 1 2 0m0-4a1 1 0 1 1-2 0a1 1 0 0 1 2 0m-5 4a1 1 0 1 1-2 0a1 1 0 0 1 2 0m0-4a1 1 0 1 1-2 0a1 1 0 0 1 2 0m-5 4a1 1 0 1 1-2 0a1 1 0 0 1 2 0m0-4a1 1 0 1 1-2 0a1 1 0 0 1 2 0"/></g></svg>
                                <svg v-else xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 512 512"><path fill="currentColor" d="M256 32c14.2 0 27.3 7.5 34.5 19.8l216 368c7.3 12.4 7.3 27.7.2 40.1S486.3 480 472 480H40c-14.3 0-27.6-7.7-34.7-20.1s-7-27.8.2-40.1l216-368C228.7 39.5 241.8 32 256 32m0 128c-13.3 0-24 10.7-24 24v112c0 13.3 10.7 24 24 24s24-10.7 24-24V184c0-13.3-10.7-24-24-24m32 224a32 32 0 1 0-64 0a32 32 0 1 0 64 0"/></svg>
                            </span>
                        </div>
                    </div>
                    <div v-if="props.auth.user.teams[0].role === 'owner' && props.setupWhatsapp === true" class="flex justify-between bg-slate-100 md:bg-white rounded-lg py-4 px-4">
                        <div>
                            <h2>{{ $t('Setup whatsapp') }}</h2>
                            <p class="text-sm text-slate-600">{{ $t('Setup your whatsapp API to start using your instance') }}</p>
                            <EmbeddedSignupBtn v-if="embeddedSignupActive == 1" :appId="props.appId" :configId="props.configId" :graphAPIVersion="props.graphAPIVersion"/>
                            <Link v-else href="/settings/whatsapp" class="bg-primary text-white p-2 rounded-lg text-sm mt-5 flex px-3 w-fit">
                                {{ $t('Setup whatsapp') }}
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><g fill="currentColor" fill-rule="evenodd" clip-rule="evenodd"><g opacity=".2"><path d="M12.206 5.848a1.5 1.5 0 0 1 2.113.192l3.333 4a1.5 1.5 0 1 1-2.304 1.92l-3.334-4a1.5 1.5 0 0 1 .192-2.112Z"/><path d="M12.206 16.152a1.5 1.5 0 0 1-.192-2.112l3.334-4a1.5 1.5 0 0 1 2.304 1.92l-3.333 4a1.5 1.5 0 0 1-2.113.192Z"/><path d="M16 11a1.5 1.5 0 0 1-1.5 1.5h-8a1.5 1.5 0 0 1 0-3h8A1.5 1.5 0 0 1 16 11Z"/></g><path d="M11.347 5.616a.5.5 0 0 1 .704.064l3.333 4a.5.5 0 0 1-.768.64l-3.333-4a.5.5 0 0 1 .064-.704Z"/><path d="M11.347 14.384a.5.5 0 0 1-.064-.704l3.333-4a.5.5 0 0 1 .768.64l-3.333 4a.5.5 0 0 1-.704.064Z"/><path d="M15.5 10a.5.5 0 0 1-.5.5H5a.5.5 0 0 1 0-1h20a.5.5 0 0 1 .5.5Z"/></g></svg>
                            </Link>
                        </div>
                        <div class="flex">
                            <span class="bg-primary h-16 w-16 p-3 flex justify-center items-center rounded-full self-start">
                                <svg class="text-white" xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24"><path fill="currentColor" d="M8.886 7.17c.183.005.386.015.579.443c.128.285.343.81.519 1.238c.137.333.249.607.277.663c.064.128.104.275.02.448l-.028.058a1.43 1.43 0 0 1-.23.37a9.386 9.386 0 0 0-.143.17c-.085.104-.17.206-.242.278c-.129.128-.262.266-.114.522c.149.256.668 1.098 1.435 1.777a6.634 6.634 0 0 0 1.903 1.2c.07.03.127.055.17.076c.257.128.41.108.558-.064c.149-.173.643-.749.817-1.005c.168-.256.34-.216.578-.128c.238.089 1.504.71 1.761.837l.143.07c.179.085.3.144.352.23c.064.109.064.62-.148 1.222c-.218.6-1.267 1.176-1.742 1.22l-.135.016c-.436.052-.988.12-2.956-.655c-2.426-.954-4.027-3.32-4.35-3.799a2.768 2.768 0 0 0-.053-.076l-.006-.008c-.147-.197-1.048-1.402-1.048-2.646c0-1.19.587-1.81.854-2.092l.047-.05a.95.95 0 0 1 .687-.32c.173 0 .347 0 .495.005Z"/><path fill="currentColor" fill-rule="evenodd" d="M2.184 21.331a.4.4 0 0 0 .487.494l4.607-1.204a10 10 0 0 0 4.76 1.207h.004c5.486 0 9.958-4.447 9.958-9.912a9.828 9.828 0 0 0-2.914-7.011A9.917 9.917 0 0 0 12.042 2c-5.486 0-9.958 4.446-9.958 9.911c0 1.739.458 3.447 1.33 4.954l-1.23 4.466Zm2.677-4.068a1.5 1.5 0 0 0-.148-1.15a8.377 8.377 0 0 1-1.129-4.202c0-4.63 3.793-8.411 8.458-8.411c2.27 0 4.388.877 5.986 2.468a8.328 8.328 0 0 1 2.472 5.948c0 4.63-3.793 8.412-8.458 8.412h-.005a8.5 8.5 0 0 1-4.044-1.026a1.5 1.5 0 0 0-1.094-.132l-2.762.721l.724-2.628Z" clip-rule="evenodd"/></svg>
                            </span>
                        </div>
                    </div>
                    <div v-if="props.auth.user.teams[0].role === 'owner' && displayTeamNotification() === true" class="bg-slate-100 md:bg-white rounded-lg py-4 px-4">
                        <div class="flex justify-between">
                            <div>
                                <h2>{{ $t('Setup team') }}</h2>
                                <p class="text-sm">{{ $t('Add other people to your team') }}</p>
                            </div>
                            <div class="flex">
                                <span class="bg-[#ffe5b4] h-16 w-16 p-3 flex justify-center items-center rounded-full self-start">
                                    <svg class="text-slate-600" xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 20 20"><g fill="currentColor"><g opacity=".2"><path d="M9.75 7.75a3 3 0 1 1-6 0a3 3 0 0 1 6 0Z"/><path fill-rule="evenodd" d="M6.75 8.75a1 1 0 1 0 0-2a1 1 0 0 0 0 2Zm0 2a3 3 0 1 0 0-6a3 3 0 0 0 0 6Z" clip-rule="evenodd"/><path fill-rule="evenodd" d="M6.8 11.5A1.5 1.5 0 0 0 5.3 13v1.5a1 1 0 0 1-2 0V13a3.5 3.5 0 0 1 7 0v.5a1 1 0 1 1-2 0V13a1.5 1.5 0 0 0-1.5-1.5Z" clip-rule="evenodd"/><path d="M12.75 7.75a3 3 0 1 0 6 0a3 3 0 0 0-6 0Z"/><path fill-rule="evenodd" d="M15.75 8.75a1 1 0 1 1 0-2a1 1 0 0 1 0 2Zm0 2a3 3 0 1 1 0-6a3 3 0 0 1 0 6Z" clip-rule="evenodd"/><path fill-rule="evenodd" d="M15.7 11.5a1.5 1.5 0 0 1 1.5 1.5v1.5a1 1 0 1 0 2 0V13a3.5 3.5 0 0 0-7 0v.5a1 1 0 1 0 2 0V13a1.5 1.5 0 0 1 1.5-1.5Z" clip-rule="evenodd"/><path fill-rule="evenodd" d="M11.3 14.25a1.5 1.5 0 0 0-1.5 1.5v1.5a1 1 0 0 1-2 0v-1.5a3.5 3.5 0 0 1 7 0v1.5a1 1 0 1 1-2 0v-1.5a1.5 1.5 0 0 0-1.5-1.5Z" clip-rule="evenodd"/><path d="M14.25 10.5a3 3 0 1 1-6 0a3 3 0 0 1 6 0Z"/><path fill-rule="evenodd" d="M11.25 11.5a1 1 0 1 0 0-2a1 1 0 0 0 0 2Zm0 2a3 3 0 1 0 0-6a3 3 0 0 0 0 6Z" clip-rule="evenodd"/><path d="M4.25 11.5h5v4h-5v-4Zm9 0h5v4h-5v-4Z"/><path d="M9.25 13.5h4l.5 4.75h-5l.5-4.75Z"/></g><path fill-rule="evenodd" d="M5 9a2 2 0 1 0 0-4a2 2 0 0 0 0 4Zm0 1a3 3 0 1 0 0-6a3 3 0 0 0 0 6Z" clip-rule="evenodd"/><path fill-rule="evenodd" d="M3.854 8.896a.5.5 0 0 1 0 .708l-.338.337A3.47 3.47 0 0 0 2.5 12.394v1.856a.5.5 0 1 1-1 0v-1.856a4.47 4.47 0 0 1 1.309-3.16l.337-.338a.5.5 0 0 1 .708 0Zm11.792-.3a.5.5 0 0 0 0 .708l.338.337A3.469 3.469 0 0 1 17 12.094v2.156a.5.5 0 0 0 1 0v-2.156a4.47 4.47 0 0 0-1.309-3.16l-.337-.338a.5.5 0 0 0-.708 0Z" clip-rule="evenodd"/><path fill-rule="evenodd" d="M14 9a2 2 0 1 1 0-4a2 2 0 0 1 0 4Zm0 1a3 3 0 1 1 0-6a3 3 0 0 1 0 6Zm-4.5 3.25a2.5 2.5 0 0 0-2.5 2.5v1.3a.5.5 0 0 1-1 0v-1.3a3.5 3.5 0 0 1 7 0v1.3a.5.5 0 1 1-1 0v-1.3a2.5 2.5 0 0 0-2.5-2.5Z" clip-rule="evenodd"/><path fill-rule="evenodd" d="M9.5 11.75a2 2 0 1 0 0-4a2 2 0 0 0 0 4Zm0 1a3 3 0 1 0 0-6a3 3 0 0 0 0 6Z" clip-rule="evenodd"/></g></svg>
                                </span>
                            </div>
                        </div>
                        <div class="flex justify-between space-x-3">
                            <Link href="team" class="bg-[#ffe5b4] p-2 rounded-lg text-sm flex px-3 capitalize">
                                {{ $t('Add users') }}
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><g fill="currentColor" fill-rule="evenodd" clip-rule="evenodd"><g opacity=".2"><path d="M12.206 5.848a1.5 1.5 0 0 1 2.113.192l3.333 4a1.5 1.5 0 1 1-2.304 1.92l-3.334-4a1.5 1.5 0 0 1 .192-2.112Z"/><path d="M12.206 16.152a1.5 1.5 0 0 1-.192-2.112l3.334-4a1.5 1.5 0 0 1 2.304 1.92l-3.333 4a1.5 1.5 0 0 1-2.113.192Z"/><path d="M16 11a1.5 1.5 0 0 1-1.5 1.5h-8a1.5 1.5 0 0 1 0-3h8A1.5 1.5 0 0 1 16 11Z"/></g><path d="M11.347 5.616a.5.5 0 0 1 .704.064l3.333 4a.5.5 0 0 1-.768.64l-3.333-4a.5.5 0 0 1 .064-.704Z"/><path d="M11.347 14.384a.5.5 0 0 1-.064-.704l3.333-4a.5.5 0 0 1 .768.64l-3.333 4a.5.5 0 0 1-.704.064Z"/><path d="M15.5 10a.5.5 0 0 1-.5.5H5a.5.5 0 0 1 0-1h20a.5.5 0 0 1 .5.5Z"/></g></svg>
                            </Link>
                            <div class="self-end">
                                <span @click="dismissNotification()" class="text-sm underline cursor-pointer">{{ $t('Dismiss notification') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg py-4 md:px-4">
                        <div>
                            <h2>{{ $t('Campaigns') }}</h2>
                            <p class="text-sm">{{ $t('Below are your outgoing or scheduled campaigns') }}</p>
                            <div v-if="props.campaigns.length === 0" class="p-5 border-2 border-dashed mt-4">
                                <p class="text-sm text-center">{{ $t('You do not have any outgoing or scheduled campaigns') }}</p>
                                <div class="flex justify-center items-center">
                                    <Link href="/campaigns/create" class="bg-primary text-white p-2 rounded-lg text-sm mt-5 flex px-3 w-fit">
                                        {{ $t('Create campaign') }}
                                    </Link>
                                </div>
                            </div>
                            <div v-else class="space-y-1">
                                <div v-for="(item, index) in props.campaigns" :key="index" class="mt-4 border-l-4 bg-gray-50 flex justify-between p-2 text-sm">
                                    <div class="capitalize">{{ item.name }}</div>
                                    <div class="self-end">
                                        <span class="bg-[#ffe5b4] p-1 rounded-md text-xs px-2 capitalize">{{ item.status }}</span>
                                    </div>
                                </div>
                                <Link href="/campaigns" class="flex items-center space-x-1 underline pt-3 text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 5H8.2c-1.12 0-1.68 0-2.108.218a1.999 1.999 0 0 0-.874.874C5 6.52 5 7.08 5 8.2v7.6c0 1.12 0 1.68.218 2.108a2 2 0 0 0 .874.874c.427.218.987.218 2.105.218h7.606c1.118 0 1.677 0 2.104-.218c.377-.192.683-.498.875-.874c.218-.428.218-.987.218-2.105V14m1-5V4m0 0h-5m5 0l-7 7"/></svg>
                                    <span>{{ $t('View more campaigns') }}</span>
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
<script setup>
    import AppLayout from "./Layout/App.vue";
    import { Link, router } from "@inertiajs/vue3";
    import { ref, onMounted } from 'vue';
    import { trans } from 'laravel-vue-i18n';
    import EmbeddedSignupBtn from '@/Components/EmbeddedSignupBtn.vue';

    const props = defineProps({ 
        user: Object, 
        auth: Object, 
        subscription: Object, 
        subscriptionIsActive: Boolean, 
        subscriptionDetails: Object, 
        chatCount: Number, 
        contactCount: Number, 
        campaignCount: Number, 
        templateCount: Number, 
        setupWhatsapp: Boolean, 
        organization: Object, 
        campaigns: Object, 
        period: Object, 
        inbound: Object, 
        outbound: Object,
        embeddedSignupActive: Number,
        appId: String,
        configId: String,
        graphAPIVersion: String,
    });

    const chartOptions = {
        chart: {
            height: 350,
            type: 'area',
            toolbar: {
                show: false
            },
            zoom: {
                enabled: false
            },
        },
        colors: ['#418207'],
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.7,
                opacityTo: 0.9,
                stops: [0, 90, 100]
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            width: 1,
            curve: 'smooth'
        },
        xaxis: {
            type: 'datetime',
            categories: props.period
        },
        tooltip: {
            x: {
                format: 'dd/MM/yy HH:mm'
            },
        },
    };

    const series = [
        {
            name: trans('Inbound chats'),
            data: props.inbound
        }, 
        {
            name: trans('Outbound chats'),
            data: props.outbound
        }
    ];

    const teamNotification = ref(true);
    const displayTeamNotification = () => {
        try {
            teamNotification.value = props.organization?.metadata
                ? JSON.parse(props.organization.metadata)?.notification?.team ?? true
                : true;
        } catch (error) {
            teamNotification.value = true;
        }

        return teamNotification.value;
    }

    const dismissNotification = () => {
        router.delete('/dismiss-notification/team', {})
    }
</script>