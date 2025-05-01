<template>
  <AppLayout>
    <div class="bg-white md:bg-inherit pt-0 px-4 md:pt-8 md:p-8 rounded-[5px] text-[#000] h-full overflow-y-scroll">
      <div class="md:flex justify-between hidden">
        <div>
          <h1 class="text-xl mb-1">{{ $t('WhatsAppWidget') }}</h1>
          <p class="mb-6 flex items-center text-sm leading-6 text-gray-600">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11v5m0 5a9 9 0 1 1 0-18a9 9 0 0 1 0 18Zm.05-13v.1h-.1V8h.1Z"/></svg>
            <span class="ml-1 mt-1">{{ $t('Generate your WhatsApp widget free of cost') }}</span>
          </p>
        </div>
        <div class="space-x-2 flex items-center">
          <Link href="/tools" class="md:block hidden rounded-md bg-indigo-600 px-3 py-2 text-sm text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">{{ $t('Back') }}</Link>
        </div>
      </div>

      <div class="flex flex-col sm:flex-row gap-6">
  <!-- Form Section (70%) -->
  <div class="md:w-3/5 border rounded bg-gray-100 p-4">
  <form v-if="tab === 'whatsappwidget'" @submit.prevent="submitForm()" class="w-full bg-white py-5 px-5 rounded-bl-[0.5rem] rounded-br-[0.5rem] ">
    
    <!-- Basic Information -->
    <div class="border-b py-5">
      <h3 class="text-sm tracking-[0px] font-semibold">{{ $t('Basic Information') }}</h3>
      <div class="grid gap-4 sm:grid-cols-2">
        <FormInput required v-model="formData.businessName" :name="$t('Business Name')" :type="'text'" />
        <FormInput required v-model="formData.phone" :name="$t('WhatsApp Number')" :type="'text'" />
        <FormInput required v-model="formData.subHeading" :name="$t('Status Message')" :type="'text'" class="sm:col-span-2"/>
        <FormInput :required="!props.widget?.profile_image" :name="$t('Profile Image')"   :type="'file'"   @change="handleFileUpload" />
        <FormSelect required v-model="formData.position" :name="$t('Widget Position')" :options="widgetPositionOptions"/>
      </div>
    </div>

    <!-- Chat Configuration -->
    <div class="border-b py-5">
      <h3 class="text-sm tracking-[0px] font-semibold">{{ $t('Chat Configuration') }}</h3>
      <div class="grid gap-4 sm:grid-cols-2"> 
<label for="name" class="block text-sm leading-6 text-gray-900">Welcome Message</label>
<textarea v-model="formData.mainMessage" required class="sm:col-span-2 border h-32 rounded p-2 w-full" ></textarea>
<label for="name" class="block text-sm leading-6 text-gray-900">Secondary Message</label>
<textarea id="subMessage" v-model="formData.subMessage"   required   class="sm:col-span-2 h-22 w-full border rounded-md p-2"  ></textarea>

      </div>
    </div>

    <!-- Quick Reply Buttons -->
    <div class="border-b py-5">
      <h3 class="text-sm tracking-[0px] font-semibold">{{ $t('Quick Reply Buttons') }}</h3>
      <div class="grid gap-4 sm:grid-cols-2">
        <FormInput required v-model="formData.buttonOne" :name="$t('Button 1')" :type="'text'" class="sm:col-span-2" />
        <FormInput v-model="formData.buttonTwo" :name="$t('Button 2')" :type="'text'" class="sm:col-span-2"/>
        <FormInput v-model="formData.buttonThree" :name="$t('Button 3')" :type="'text'" class="sm:col-span-2"/>
      </div>
    </div>

    <!-- Submit Button -->
    <div class="py-6">
      <button type="submit" class="float-right flex items-center space-x-4 rounded-md bg-black px-3 py-2 text-sm text-white shadow-sm hover:bg-slate-600">
        {{ $t('Generate Widget Code') }}
      </button>
    </div>

  </form>
  </div>

  <!-- Preview Section (30%) -->
  <div class="md:w-2/5 border rounded bg-gray-100 p-4">
    <div class="whatsapp-popup" style="width: 350px;">
      <div class="whatsapp-popup-header flex items-center">
         <img id="previewImg" :src="formData.profileImage ? '../uploads/whatsapp_widget/'+formData.profileImage : (props.widget?.profile_image ? '../uploads/whatsapp_widget/'+props.widget.profile_image : '')" alt="Profile Image Preview"  class="w-16 h-16 rounded-full object-cover mr-4"/>
        <div>
          <h4 class="font-semibold">{{ formData.businessName || "Bulkwise Automations" }}</h4>
          <p class="text-sm  ">{{ formData.subHeading || "Typically replies within 15 minutes" }}</p>
        </div>
      </div>
      <div class="whatsapp-popup-body">
        <div class="message bot">{{ formData.mainMessage || "Hello! How can we help you today?" }}</div>
        <div class="message bot">{{ formData.subMessage || "Select an option below or type your message" }}</div>
        <div class="button-container mt-4">
          <button class="px-4 py-2 bg-green-500 text-white rounded">{{ formData.buttonOne || "Get a Quote" }}</button>
          <button class="px-4 py-2 bg-green-500 text-white rounded">{{ formData.buttonTwo || "Make an Order" }}</button>
          <button class="px-4 py-2 bg-green-500 text-white rounded">{{ formData.buttonThree || "Ask a Question" }}</button>
        </div>
      </div>
      <div class="powered-by">
      </div>
    </div>
  </div>
</div>



    <!-- Modal Section -->
    <div v-if="showModal" class="modal-overlay" @click.self="closeModal">
      <div class="modal-content">
        <button class="close-btn" @click="closeModal">X</button>
        <h2>Your WhatsApp Widget Code</h2>
        <p>Copy and paste this code in your website header, just before the closing body tag of your website:</p>
        <div class="widget-code">
        <pre id="scriptCode">{{ scriptCode }}</pre>
        </div>
        <div class="copy-btn" @click="copyCode"><button>Copy Code</button></div>
      </div>
    </div>
      
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from "./../Layout/App.vue";
import { ref } from 'vue';
import { Link, useForm } from "@inertiajs/vue3";
import FormInput from '@/Components/FormInput.vue';
import FormSelect from '@/Components/FormSelect.vue';
import { trans } from 'laravel-vue-i18n';
import axios from 'axios';

const props = defineProps({
  widget: {
    type: Object,
    default: () => ({
      business_name: '',
      whatsapp_number: '',
      status_message: '',
      profile_image: '',
      position: '',
      welcome_message: '',
      secondary_message: '',
      button1: '',
      button2: '',
      button3: ''
    })
  }
});

const widget = props.widget || {
  business_name: '',
  whatsapp_number: '',
  status_message: '',
  profile_image: '',
  position: '',
  welcome_message: '',
  secondary_message: '',
  button1: '',
  button2: '',
  button3: ''
};

const getProfileImage = async () => {
  try {
    const response = await axios.get('/tools/getprofileimage');
    if (response.data && response.data.profile_image) {
      formData.value.profileImage = `../uploads/whatsapp_widget/${response.data.profile_image}`;
    }
  } catch (error) {
    console.error('Error fetching profile image:', error);
  }
};

const handleFileUpload = (event) => {
  const file = event.target.files[0];
  const formData = new FormData();
  formData.append('profile_image', file);

  axios.post('/tools/whatsapp-widget/upload-profile-image', formData, {
    headers: {
      'Content-Type': 'multipart/form-data'
    }
  })
  .then(response => {
     document.getElementById('previewImg').src = '../uploads/whatsapp_widget/'+ response.data + '?' + new Date().getTime();
  });
};

const tab = ref('whatsappwidget');

const scriptCode = ref('');
const selectedPosition = ref('right'); // set default position
const widgetPositionOptions = ref([
  { value: 'right', label: trans('Bottom Right') },
  { value: 'left', label: trans('Bottom Left') },
]);

const isLoading = ref(false);
const showModal = ref(false);

const formData = ref({
  businessName: props.widget?.business_name ?? '',
  phone: props.widget?.whatsapp_number ?? '',
  subHeading: props.widget?.status_message ?? '',
  profileImage: props.widget?.profile_image ?? '',
  position: props.widget?.widget_position ?? '',
  mainMessage: props.widget?.welcome_message ?? '',
  subMessage: props.widget?.secondary_message ?? '',
  buttonOne: props.widget?.button1 ?? '',
  buttonTwo: props.widget?.button2 ?? '',
  buttonThree: props.widget?.button3 ?? ''
});

// Function to simulate async operation
const submitForm = async () => {
  try {
    // Simulate an async operation (like an API call) with a delay
    await new Promise(resolve => setTimeout(resolve, 1000));

    const formDataObj = new FormData();
    Object.entries(formData.value).forEach(([key, value]) => {
      formDataObj.append(key, value);
    });

    const queryString = Object.entries(formData.value)
    .map(([key, value]) => {
      if (key === 'profileImage') {
        value = `${window.location.origin}/uploads/whatsapp_widget/${value}`;
      }
      return `${key}=${encodeURIComponent(value)}`;
    })
    .join('&');

    const currentUrl = window.location.origin;
    scriptCode.value = `<!-- WhatsApp Chat Widget Code -->\n<script src="${currentUrl}/tools-scripts/widget/whatsapp-popup.js?${queryString}"><\/script>`;
    showModal.value = true;

    console.log(formData.value.profileImage)

     const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const response = await fetch('/tools/save-whatsapp-widget', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken
      },
      body: JSON.stringify(formData.value),
    });

    if (!response.ok) {
      throw new Error('Failed to save form data');
    }

    const result = await response.json();
    console.log('Form data saved successfully:', result);

  } catch (error) {
    console.error('Error submitting form:', error);
  }
};

// Function to close the modal
const closeModal = () => {
  showModal.value = false;
  removeWhatsAppWidget();
};

const copyCode = () => {
  const code = scriptCode.value; // Get the code from the scriptCode ref
  navigator.clipboard.writeText(code).then(() => {
    const copyButton = document.querySelector('.copy-btn button');
    copyButton.textContent = 'Copied';
    setTimeout(() => {
      copyButton.textContent = 'Copy Code';
    }, 3000);
  }).catch(err => {
    console.error("Error copying code: ", err);
  });
};
</script>


<style scoped>
/* Loading indicator */
.loading {
  margin-top: 10px;
  color: #ff9900;
}

/* Modal overlay (background) */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

/* Modal content */
.modal-content {
  background-color: white;
  padding: 20px;
  border-radius: 8px;
  width: 60%;
  max-width: 600px;
  position: relative;
}

/* Close button */
.close-btn {
  position: absolute;
  top: 10px;
  right: 10px;
  background: none;
  border: none;
  font-size: 14px;
  cursor: pointer;
  color: red;
}

/* Modal body */
.modal-body {
  font-size: 16px;
}
.modal-content > p {
    font-size: 12px;
    color: #9b9595;
    margin: 5px 3px 5px 0px;
}
/* Ensure the WhatsApp widget is at the bottom-right */
.whatsapp-widget {
  position: fixed;
  bottom: 20px;
  right: 20px;
  z-index: 1000; /* Set a lower z-index to prevent overlapping the modal */
  display: flex;
}

/* Modal Box */
.modal {
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  z-index: 1050; /* Higher than WhatsApp widget */
  display: none; /* Hide by default */
  /* Add your modal styling here */
}
pre {
    font-family: monospace, monospace;
    font-size: 1em;
    white-space: pre-wrap;
}
.copy-btn {
  margin-top: 10px;
  background: rgb(37, 211, 102);
  color: white;
  text-align: center;
  padding: 5px;
  cursor: pointer;
}
.widget-code {
    padding: 10px;
    margin: 0;
    color: #e6e6e6;
    /* font-family: 'Consolas', 'Monaco', monospace; */
    font-size: 12px;
    line-height: 1.5;
    white-space: pre-wrap;
    word-wrap: break-word;
    background: black;
    border-radius: 8px;
}
.preview-area
{ 
}
.preview-section {
  position: sticky;
  top: 2rem;
  background: #f8f9fa;
  border-radius: 15px;
  padding: 1.5rem;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  height: fit-content; 
  width: 400px;
  max-width: 400px;
}

.preview-header {
  margin-bottom: 1rem;
  padding-bottom: 1rem;
  border-bottom: 1px solid #e0e0e0;
}

.preview-container {
  background: white;
  
}

.preview-frame {
  width: 100%;
  height: 600px;
  border: none;
}
.whatsapp-popup {
    position: relative !important;
    bottom: auto !important;
    right: auto !important;
    left: auto !important; 
    opacity: 1 !important;
    transform: none !important;
    pointer-events: auto !important;
    visibility: visible !important;
    margin: 0 auto;
}

.whatsapp-popup-header {
    background-color: #075e54;
    color: white;
    padding: 15px;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
    display: flex;
    align-items: center;
    font-weight: bold;
    position: relative;
}

.header-image {
    width: 40px;
    height: 40px;
    border-radius: 50%;
}

.header-text {
    margin-left: 10px;
}

.header-text h4 {
    margin: 0;
    font-size: 18px;
    color: white;
}

.header-text p {
    margin: 0;
    line-height: 1.2;
    font-size: 14px;
}

.whatsapp-popup-body {
    padding: 15px;
    flex: 0 1 auto;
    display: flex;
    flex-direction: column;
    background-color: #e5ddd5;
    background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyBAMAAADsEZWCAAAAG1BMVEUAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAr8i/3AAAAB3RSTlMI/wD/AP8A/90SdgAAAIhJREFUOMtjYBgFgwwICAhwADGDgABPAIhPEwBSQkxMXEFBQUwMQAzKBCrg4eEJ4OHhYQJyGRkZGZmYgMqYgk0NDQ1FTU0FgUxGoCImIC2AJMTIBKQuAGIyMwWAmIKCggJMQKYAkBkEEgIqg7ExwaYzwpyPaS9CBDDsRYgAhr0IEcCwFyECRgEeAADQyyqIlJkqrAAAAABJRU5ErkJggg==');
}

.message {
    background-color: #DCF8C6;
    padding: 10px;
    border-radius: 10px;
    margin: 5px 0;
    max-width: 80%;
    display: inline-block;
    color: black;
}

.message.bot {
    align-self: flex-start;
    background-color: #ffffff;
    border: 1px solid #ccc;
    max-width: 400px;
}

.button-container {
    display: flex;
    flex-direction: column;
    margin-top: auto;
    padding-top: 10px;
}

.button-container button {
    padding: 10px;
    border: none;
    background-color: #25d366;
    color: white;
    border-radius: 5px;
    cursor: pointer;
    margin-bottom: 5px;
    transition: background-color 0.3s;
}

.button-container button:hover {
    background-color: #1da85b;
}

.powered-by {
    flex: 0 0 auto;
    text-align: center;
    font-size: 12px;
    margin-bottom: 0;
    padding: 8px 0;
    background-color: #f8f9fa;
    border-top: 1px solid #eee;
    position: relative;
    z-index: 2;
    border-bottom-left-radius: 10px;
    border-bottom-right-radius: 10px;
    background-color: #075e54;
}
</style>