<template>
    <div class="flex min-h-screen align-center justify-center bg-gray-50">
        <div class="">
            <div class="w-[21em] md:w-[26.5em] bg-white shadow-md px-5 pt-10 pb-10 rounded-2xl mb-20 mt-20">
                <div class="flex justify-center mb-5">
                    <Link href="/">
                        <img class="max-w-[180px]" v-if="props.companyConfig.logo" :src="'/media/' + props.companyConfig.logo" :alt="props.companyConfig.company_name">
                        <h4 v-else class="text-2xl mb-2">{{ props.companyConfig.company_name }}</h4>
                    </Link>
                </div>
                <h1 class="text-2xl text-center text-gray-600">{{ $t('Login to your account') }}</h1>
                <form @submit.prevent="submitForm()" class="mt-5">
                    <div class="mt-5 space-y-4">
                        <FormInput v-model="form.email" :name="$t('Email address')+':'" :error="form.errors.email" :type="'email'" inputIcon='<i class="pi pi-at"></i>' :inputClass="`py-2.5 focus:border-green-200 focus:border-2 focus:ring-green-500 focus:ring-2`" :class="'col-span-3'"/>
                        <FormInput v-model="form.password" :name="$t('Password')+':'" :error="form.errors.password" :type="'password'" inputIcon='<i class="pi pi-lock"></i>' inputClass="py-1.5" :class="'col-span-3'" id="password" :eyeEnable="true" @viewPassword="viewPassword"/>
                        <div v-if="form.errors.recaptcha_response" class="form-error text-[#b91c1c] text-xs">{{ form.errors.recaptcha_response }}</div>
                    </div>
                    <div class="flex items-center justify-between mt-1">
                        <Link href="/forgot-password" class="text-sm text-primary-600 dark:text-primary-500 border-b hover:border-gray-500">{{ $t('Forgot password?') }}</Link>
                    </div>
                    <div class="flex items-center justify-between mt-5 hidden">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="remember" aria-describedby="remember" type="checkbox" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-primary-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-primary-600 dark:ring-offset-gray-800">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="remember" class="text-gray-500 dark:text-gray-300">{{ $t('Remember me') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6">
                        <button v-if="!isLoading" type="submit" class="rounded-md bg-green-600 hover:bg-green-700 px-3 py-3 text-sm text-white shadow-sm w-full">{{ $t('Login to your account') }}</button>
                        <button v-else type="button" class="rounded-md bg-primary px-3 py-3 text-sm text-white shadow-sm w-full flex justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M12 2A10 10 0 1 0 22 12A10 10 0 0 0 12 2Zm0 18a8 8 0 1 1 8-8A8 8 0 0 1 12 20Z" opacity=".5"/><path fill="currentColor" d="M20 12h2A10 10 0 0 0 12 2V4A8 8 0 0 1 20 12Z"><animateTransform attributeName="transform" dur="1s" from="0 12 12" repeatCount="indefinite" to="360 12 12" type="rotate"/></path></svg>
                        </button>
                    </div>
                    <div class="text-center text-sm text-slate-500 mt-5">
                    {{ $t('Don\'t have an account?') }} 
                    <Link href="signup" class="text-sm text-primary-600 dark:text-primary-500 border-b hover:border-gray-500">{{ $t('Create one here') }}</Link>
                    </div>
                </form>
                <div v-if="props.companyConfig?.allow_facebook_login === '1' || props.companyConfig?.allow_google_login === '1'" class="flex justify-center my-6">
                    <span class="text-sm text-gray-500 px-4 text-center">{{ $t('Or continue with') }}</span>
                </div>
                <div class="flex justify-center gap-4">
                    <a v-if="props.companyConfig?.allow_facebook_login === '1'" href="/social-login/facebook" class="border rounded-full p-2 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 256 256"><path fill="#1877F2" d="M256 128C256 57.308 198.692 0 128 0C57.308 0 0 57.307 0 128c0 63.888 46.808 116.843 108 126.445V165H75.5v-37H108V99.8c0-32.08 19.11-49.8 48.347-49.8C170.352 50 185 52.5 185 52.5V84h-16.14C152.958 84 148 93.867 148 103.99V128h35.5l-5.675 37H148v89.445c61.192-9.602 108-62.556 108-126.445"/><path fill="#FFF" d="m177.825 165l5.675-37H148v-24.01C148 93.866 152.959 84 168.86 84H185V52.5S170.352 50 156.347 50C127.11 50 108 67.72 108 99.8V128H75.5v37H108v89.445A128.959 128.959 0 0 0 128 256a128.9 128.9 0 0 0 20-1.555V165h29.825"/></svg>
                    </a>
                    <a v-if="props.companyConfig?.allow_google_login === '1'" href="/social-login/google" class="border rounded-full p-2 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 128 128"><path fill="#fff" d="M44.59 4.21a63.28 63.28 0 0 0 4.33 120.9a67.6 67.6 0 0 0 32.36.35a57.13 57.13 0 0 0 25.9-13.46a57.44 57.44 0 0 0 16-26.26a74.33 74.33 0 0 0 1.61-33.58H65.27v24.69h34.47a29.72 29.72 0 0 1-12.66 19.52a36.16 36.16 0 0 1-13.93 5.5a41.29 41.29 0 0 1-15.1 0A37.16 37.16 0 0 1 44 95.74a39.3 39.3 0 0 1-14.5-19.42a38.31 38.31 0 0 1 0-24.63a39.25 39.25 0 0 1 9.18-14.91A37.17 37.17 0 0 1 76.13 27a34.28 34.28 0 0 1 13.64 8q5.83-5.8 11.64-11.63c2-2.09 4.18-4.08 6.15-6.22A61.22 61.22 0 0 0 87.2 4.59a64 64 0 0 0-42.61-.38z"/><path fill="#e33629" d="M44.59 4.21a64 64 0 0 1 42.61.37a61.22 61.22 0 0 1 20.35 12.62c-2 2.14-4.11 4.14-6.15 6.22Q95.58 29.23 89.77 35a34.28 34.28 0 0 0-13.64-8a37.17 37.17 0 0 0-37.46 9.74a39.25 39.25 0 0 0-9.18 14.91L8.76 35.6A63.53 63.53 0 0 1 44.59 4.21z"/><path fill="#f8bd00" d="M3.26 51.5a62.93 62.93 0 0 1 5.5-15.9l20.73 16.09a38.31 38.31 0 0 0 0 24.63q-10.36 8-20.73 16.08a63.33 63.33 0 0 1-5.5-40.9z"/><path fill="#587dbd" d="M65.27 52.15h59.52a74.33 74.33 0 0 1-1.61 33.58a57.44 57.44 0 0 1-16 26.26c-6.69-5.22-13.41-10.4-20.1-15.62a29.72 29.72 0 0 0 12.66-19.54H65.27c-.01-8.22 0-16.45 0-24.68z"/><path fill="#319f43" d="M8.75 92.4q10.37-8 20.73-16.08A39.3 39.3 0 0 0 44 95.74a37.16 37.16 0 0 0 14.08 6.08a41.29 41.29 0 0 0 15.1 0a36.16 36.16 0 0 0 13.93-5.5c6.69 5.22 13.41 10.4 20.1 15.62a57.13 57.13 0 0 1-25.9 13.47a67.6 67.6 0 0 1-32.36-.35a63 63 0 0 1-23-11.59A63.73 63.73 0 0 1 8.75 92.4z"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
    import { Link, useForm, usePage } from "@inertiajs/vue3";
    import FormInput from '@/Components/FormInput.vue';
    import { defineProps, ref, onMounted, onUnmounted, watch } from 'vue';
    import { useRecaptcha, unMountRecaptcha } from '../../Composables/ReCaptcha';
    import { toast } from 'vue3-toastify';

    const props = defineProps(['flash', 'config', 'companyConfig']);
    const isLoading = ref(false);

    const form = useForm({
        email: null,
        password: null,
        recaptcha_response: null,
    })

    const getValueByKey = (key) => {
        const found = props.config.find(item => item.key === key);
        return found ? found.value : '';
    };

    const submitForm = async (event) => {
        isLoading.value = true;

        if(getValueByKey('recaptcha_active') === '1'){
            const token = await getRecaptchaToken();
            
            form.recaptcha_response = token;
        }

        form.post('/login', {
            onSuccess: () => form.reset(),
            onFinish: () => {
                isLoading.value = false;
            }
        });
    };

    const getRecaptchaToken = () => {
        return new Promise((resolve) => {
            // Execute reCAPTCHA and get the token
            grecaptcha.ready(() => {
                grecaptcha.execute(getValueByKey('recaptcha_site_key'), { action: 'submit' })
                .then((token) => {
                    resolve(token);
                });
            });
        });
    };

    const viewPassword = (data) => {
        var node = document.querySelector(`#${data.id} input`);
        node.setAttribute('type',node.getAttribute('type') == 'password' ? 'text' : 'password')
    }

    onMounted(() => {
        if(getValueByKey('recaptcha_active') === '1'){
            useRecaptcha(getValueByKey('recaptcha_site_key'));
        }
    });

    onUnmounted(() => {
        unMountRecaptcha(getValueByKey('recaptcha_site_key'));
    });

    watch(() => [usePage().props.flash, { deep: true }], () => {
        if(usePage().props.flash.status != null){
            toast(usePage().props.flash.status.message, {
                autoClose: 3000,
            });
        }
    });
</script>