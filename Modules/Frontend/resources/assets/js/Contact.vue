<template>
  <div class="min-h-screen bg-black flex items-center justify-center py-16 px-4">
    <div class="w-full max-w-2xl mt-24">
      <!-- Header Section -->
      <div class="mb-12 text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">{{ $t('getting_in_touch') }}</h1>
        <p class="text-gray-400 text-lg max-w-xl mx-auto">
          {{ $t('send_message_and_we_will_get_back_to_you_soon') }}
        </p>
      </div>
      
      <!-- Contact Form Card - White and glossy -->
      <div class="relative bg-white rounded-xl overflow-hidden shadow-[0_10px_50px_rgba(255,255,255,0.15)] form-glossy">
        <!-- Decorative elements -->
        <div class="absolute top-0 left-0 w-48 h-48 bg-black/5 rounded-full -translate-x-1/4 -translate-y-1/4 blur-2xl"></div>
        <div class="absolute bottom-0 right-0 w-64 h-64 bg-black/5 rounded-full translate-x-1/4 translate-y-1/4 blur-2xl"></div>
        
        <!-- Form Content -->
        <form @submit.prevent="submitForm" class="relative p-8 md:p-10">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Modern floating label input for Name -->
            <div class="form-group relative">
              <input 
                type="text" 
                id="name"
                v-model="form.name" 
                placeholder="{{ $t('name') }}"
                class="input-modern peer pt-5 pb-2 px-4 w-full bg-transparent border-b-2 border-gray-300 text-gray-800 placeholder-transparent focus:outline-none focus:border-black transition-all duration-300" 
                required 
              />
              <label 
                for="name" 
                class="absolute left-4 -top-0.5 text-sm text-gray-500 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-placeholder-shown:top-3.5 peer-focus:-top-0.5 peer-focus:text-sm peer-focus:text-black transition-all duration-300"
              >
                {{ $t('name') }}
              </label>
              <span class="input-highlight absolute bottom-0 left-0 w-0 h-0.5 bg-black group-hover:w-full transition-all duration-300"></span>
            </div>
            
            <!-- Modern floating label input for Email -->
            <div class="form-group relative">
              <input 
                type="email" 
                id="email"
                v-model="form.email" 
                placeholder="{{ $t('email') }}"
                class="input-modern peer pt-5 pb-2 px-4 w-full bg-transparent border-b-2 border-gray-300 text-gray-800 placeholder-transparent focus:outline-none focus:border-black transition-all duration-300" 
                required 
              />
              <label 
                for="email" 
                class="absolute left-4 -top-0.5 text-sm text-gray-500 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-placeholder-shown:top-3.5 peer-focus:-top-0.5 peer-focus:text-sm peer-focus:text-black transition-all duration-300"
              >
                {{ $t('email') }}
              </label>
            </div>
          </div>
          
          <!-- Modern floating label input for Subject -->
          <div class="form-group relative mb-8">
            <input 
              type="text" 
              id="subject"
              v-model="form.subject" 
              placeholder="{{ $t('subject') }}"
              class="input-modern peer pt-5 pb-2 px-4 w-full bg-transparent border-b-2 border-gray-300 text-gray-800 placeholder-transparent focus:outline-none focus:border-black transition-all duration-300" 
              required 
            />
            <label 
              for="subject" 
              class="absolute left-4 -top-0.5 text-sm text-gray-500 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-placeholder-shown:top-3.5 peer-focus:-top-0.5 peer-focus:text-sm peer-focus:text-black transition-all duration-300"
            >
              {{ $t('subject') }}
            </label>
          </div>
          
          <!-- Modern textarea with floating label -->
          <div class="form-group relative mb-12">
            <textarea 
              id="message"
              v-model="form.message" 
              rows="4"
              placeholder="{{ $t('message') }}"
              class="input-modern peer pt-5 pb-2 px-4 w-full bg-gray-50 rounded-lg border-2 border-gray-200 text-gray-800 placeholder-transparent focus:outline-none focus:ring-0 focus:border-black transition-all duration-300 resize-none" 
              required
            ></textarea>
            <label 
              for="message" 
              class="absolute left-4 top-2 text-sm text-gray-500 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-placeholder-shown:top-4 peer-focus:top-2 peer-focus:text-sm peer-focus:text-black transition-all duration-300"
            >
              {{ $t('message') }}
            </label>
          </div>
          
          <!-- Centered submit button with subtle hover animation -->
          <div class="flex flex-col items-center justify-center mb-4">
            <button 
              type="submit" 
              class="bg-black text-white font-medium py-3 px-10 rounded-full hover:bg-gray-900 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 flex items-center justify-center group"
            >
              <span class="relative">
                <span class="inline-block transition-transform duration-300 group-hover:translate-x-[-8px]">{{ $t('send') }}</span>
                <svg xmlns="https://www.w3.org/2000/svg" class="h-5 w-5 absolute right-[-28px] top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-all duration-300 transform group-hover:translate-x-[-8px]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
              </span>
            </button>
            
            <div class="mt-4">
              <transition name="fade">
                <p v-if="success" class="text-green-600 flex items-center">
                  <svg xmlns="https://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                  {{ $t('message_sent_successfully') }}
                </p>
              </transition>
              
              <transition name="fade">
                <p v-if="error" class="text-red-600 flex items-center">
                  <svg xmlns="https://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                  {{ $t('error_sending_message') }}
                </p>
              </transition>
            </div>
          </div>
        </form>
      </div>
      
      <!-- Contact Info -->
      <div class="mt-12 grid grid-cols-1 md:grid-cols-2 gap-6 text-center">
        <div class="contact-info-item">
          <div class="bg-white/5 rounded-full w-12 h-12 flex items-center justify-center mx-auto mb-4">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
            </svg>
          </div>
          <h3 class="text-white font-medium mb-1">{{ $t('phone') }}</h3>
          <p class="text-gray-400">+381 61 391 3600</p>
        </div>
        
        <div class="contact-info-item">
          <div class="bg-white/5 rounded-full w-12 h-12 flex items-center justify-center mx-auto mb-4">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
          </div>
          <h3 class="text-white font-medium mb-1">{{ $t('email') }}</h3>
          <p class="text-gray-400">photography.by.lens.legacy@gmail.com</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: "ContactPage",
  data() {
    return {
      form: {
        name: '',
        email: '',
        subject: '',
        message: ''
      },
      success: false,
      error: false
    };
  },
  methods: {
    async submitForm() {
      try {
        await axios.post('/api/contact', this.form);
        this.success = true;
        this.error = false;
        this.form.name = '';
        this.form.email = '';
        this.form.subject = '';
        this.form.message = '';
        
        // Hide success message after 5 seconds
        setTimeout(() => {
          this.success = false;
        }, 5000);
      } catch (e) {
        this.error = true;
        this.success = false;
        
        // Hide error message after 5 seconds
        setTimeout(() => {
          this.error = false;
        }, 5000);
      }
    }
  }
};
</script>

<style scoped>
/* Modern Input Field Effects */
.input-modern:focus {
  box-shadow: none;
}

/* Animated input highlight effect */
.form-group {
  position: relative;
  overflow: hidden;
}

.form-group::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  width: 0;
  height: 2px;
  background-color: black;
  transition: width 0.4s ease;
}

.form-group:focus-within::after {
  width: 100%;
}

/* Enhanced input focus animation */
.input-modern:focus ~ .input-highlight,
.input-modern:not(:placeholder-shown) ~ .input-highlight {
  width: 100%;
}

/* Glossy white form effect */
.form-glossy {
  background: linear-gradient(135deg, rgba(255,255,255,1) 0%, rgba(245,245,245,1) 100%);
  box-shadow: 
    0 10px 30px rgba(0, 0, 0, 0.2),
    0 1px 3px rgba(255, 255, 255, 0.3) inset,
    0 -1px 3px rgba(0, 0, 0, 0.05) inset;
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.8);
  position: relative;
  overflow: hidden;
}

.form-glossy::before {
  content: '';
  position: absolute;
  top: 0;
  left: -50%;
  width: 200%;
  height: 100px;
  background: rgba(255, 255, 255, 0.5);
  transform: rotate(-5deg) translateY(-70px);
  pointer-events: none;
}

/* Fade transition for success/error messages */
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.5s ease;
}

.fade-enter-from, .fade-leave-to {
  opacity: 0;
}

/* Hover effect for contact info items */
.contact-info-item {
  transition: transform 0.3s ease;
}

.contact-info-item:hover {
  transform: translateY(-5px);
}

/* Background subtle pattern */
.bg-black {
  background-color: black;
  background-image: radial-gradient(circle at 50% 50%, #111 0%, black 100%);
  background-attachment: fixed;
}

/* Submit button hover animation */
button[type="submit"]:hover {
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
}
</style>