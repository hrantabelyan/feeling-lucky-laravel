<template>
    <ul v-if="history.length" class="list-none p-0 m-0 text-left">
        <li v-for="(item, index) in history" :key="index" class="border-b border-[#eee] py-2 text-[0.9rem]">
            {{ item.result === true ? 'Win' : 'Lose' }} ({{ item.random_number }}) - Win: {{ item.win_amount }}
        </li>
    </ul>
    <p v-else class="text-[#888] text-[0.9rem]">No history yet.</p>
</template>

<script setup>
defineProps({
    history: {
        type: Array,
        required: true
    }
});
</script>
