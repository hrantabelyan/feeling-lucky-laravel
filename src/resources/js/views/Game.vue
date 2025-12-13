<template>
    <div class="w-full max-w-[400px] bg-white p-8 rounded-lg shadow-[0_4px_6px_rgba(0,0,0,0.1)] text-center" v-if="isValid">
        <h1 class="mb-6 text-[#333] text-2xl font-bold">Page A</h1>
        <p class="mb-2 text-[#333]">Welcome, <strong>{{ username }}</strong>!</p>
        <p class="text-[0.8rem] text-[#888] mb-6">Unique Link: {{ currentLink }}</p>

        <div v-if="currentResult" class="mt-6 p-4 rounded border" 
             :class="currentResult.result === true ? 'bg-[#d4edda] text-[#155724] border-[#c3e6cb]' : 'bg-[#f8d7da] text-[#721c24] border-[#f5c6cb]'">
            <p>Random Number: <strong>{{ currentResult.random_number }}</strong></p>
            <p>Result: <strong>{{ currentResult.result ? 'Win' : 'Lose' }}</strong></p>
            <p>Win Amount: <strong>{{ currentResult.win_amount }}</strong></p>
        </div>

        <div class="mt-8">
            <button @click="playLucky" class="w-full p-3 bg-[#007bff] text-white rounded text-base cursor-pointer hover:bg-[#0056b3] transition-colors">
                I'm feeling lucky
            </button>
            <button @click="fetchHistory" class="w-full p-3 bg-[#6c757d] text-white rounded text-base cursor-pointer hover:bg-[#545b62] transition-colors mt-2">
                History
            </button>
            <button @click="regenerateLink" class="w-full p-3 bg-[#6c757d] text-white rounded text-base cursor-pointer hover:bg-[#545b62] transition-colors mt-2">
                Regenerate Link
            </button>
            <button @click="deactivateLink" class="w-full p-3 bg-[#dc3545] text-white rounded text-base cursor-pointer hover:bg-[#c82333] transition-colors mt-2">
                Deactivate Link
            </button>
        </div>

        <div v-if="showHistory" class="mt-6 text-left">
            <h3 class="font-bold mb-2">Latest 3 Results</h3>
            <HistoryList :history="historyData" :key="token" />
        </div>

    </div>
    <div v-else class="text-center mt-10">
        <h1 class="text-2xl text-red-500">Link Invalid or Expired</h1>
        <router-link to="/" class="text-blue-500 underline mt-4 block">Go to Home</router-link>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';
import HistoryList from '../components/HistoryList.vue';

const route = useRoute();
const router = useRouter();
const token = ref(route.params.token);

const isValid = ref(true);
const username = ref('');
const currentResult = ref(null);
const showHistory = ref(false);
const historyData = ref([]);

const api = axios.create({
    baseURL: '/api/v1'
});

const currentLink = computed(() => {
    return window.location.origin + '/games/' + token.value; // Keep frontend route as /games/
});

const checkLink = async () => {
    try {
        const response = await api.get(`/games/${token.value}`);
        username.value = response.data.data.username;
    } catch (e) {
        isValid.value = false;
    }
};

const playLucky = async () => {
    try {
        const response = await api.post(`/games/${token.value}/play`);
        currentResult.value = response.data;
        if (showHistory.value) fetchHistory(); // Auto update history if visible
    } catch (e) {
        alert('Error playing game: ' + e.message);
    }
};

const fetchHistory = async () => {
    try {
        const response = await api.get(`/games/${token.value}/results`);
        historyData.value = response.data.data;
        showHistory.value = true;
    } catch (e) {
        console.error(e);
    }
};

const regenerateLink = async () => {
    try {
        const response = await api.post(`/games/${token.value}/regenerate`);
        const newToken = response.data.token;
        token.value = newToken;
        router.push({ name: 'game', params: { token: newToken } });
        currentResult.value = null;
        showHistory.value = false;
        alert('Link regenerated! New URL: ' + response.data.link_url);
        checkLink(); // verify and get user
    } catch (e) {
        alert('Error regenerating link');
    }
};

const deactivateLink = async () => {
    try {
        await api.post(`/games/${token.value}/deactivate`);
        isValid.value = false;
        alert('Link deactivated.');
    } catch (e) {
        alert('Error deactivating link');
    }
};

onMounted(() => {
    checkLink();
});
</script>
