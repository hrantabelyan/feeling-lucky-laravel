<template>
    <div class="w-full max-w-[400px] bg-white p-8 rounded-lg shadow-[0_4px_6px_rgba(0,0,0,0.1)] text-center">
        <h1 class="mb-6 text-[#333] text-2xl font-bold">Register</h1>
        <form @submit.prevent="register">
            <div class="mb-4 text-left">
                <label class="block mb-2 text-[#666]">Username</label>
                <input v-model="form.username" type="text" class="w-full p-3 border border-[#ddd] rounded box-border" required />
                <span v-if="errors.username" class="text-red-500 text-sm mt-1 block">{{ errors.username[0] }}</span>
            </div>
            <div class="mb-4 text-left">
                <label class="block mb-2 text-[#666]">Phonenumber</label>
                <input v-model="form.phonenumber" type="text" class="w-full p-3 border border-[#ddd] rounded box-border" required placeholder="+1234567890" />
                <span v-if="errors.phonenumber" class="text-red-500 text-sm mt-1 block">{{ errors.phonenumber[0] }}</span>
            </div>
            <button type="submit" class="w-full p-3 bg-[#007bff] text-white rounded text-base cursor-pointer hover:bg-[#0056b3] transition-colors">Register</button>
        </form>
    </div>
</template>

<script setup>
import { reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';

const router = useRouter();
const form = reactive({
    username: '',
    phonenumber: ''
});

const errors = ref({});

const register = async () => {
    errors.value = {};
    try {
        const response = await axios.post('/api/v1/register', form);
        const token = response.data.token;
        router.push({ name: 'game', params: { token } });
    } catch (error) {
        if (error.response?.status === 422) {
            errors.value = error.response.data.errors;
        } else {
            errors.value = { general: ['Something went wrong'] };
            console.error(error);
        }
    }
};
</script>
