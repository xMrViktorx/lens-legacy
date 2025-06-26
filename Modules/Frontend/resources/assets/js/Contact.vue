<template>
  <div class="min-h-screen bg-black flex items-center justify-center">
    <form @submit.prevent="submitForm" class="bg-white p-6 rounded shadow-md w-full max-w-md">
      <h2 class="text-2xl font-bold mb-4">Contact Us</h2>

      <div class="mb-4">
        <label class="block text-gray-700 mb-1">Name</label>
        <input type="text" v-model="form.name" class="w-full p-2 border rounded" required />
      </div>

      <div class="mb-4">
        <label class="block text-gray-700 mb-1">Email</label>
        <input type="email" v-model="form.email" class="w-full p-2 border rounded" required />
      </div>

      <div class="mb-4">
        <label class="block text-gray-700 mb-1">Subject</label>
        <input type="text" v-model="form.subject" class="w-full p-2 border rounded" required />
      </div>

      <div class="mb-4">
        <label class="block text-gray-700 mb-1">Message</label>
        <textarea v-model="form.message" class="w-full p-2 border rounded" required></textarea>
      </div>

      <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        Send Message
      </button>

      <p v-if="success" class="text-green-600 mt-4">Message sent successfully!</p>
      <p v-if="error" class="text-red-600 mt-4">Something went wrong.</p>
    </form>
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
      } catch (e) {
        this.error = true;
        this.success = false;
      }
    }
  }
};
</script>