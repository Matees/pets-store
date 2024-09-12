<template>
  <div>
    RESULT: {{message}}
    <button @click="fetchInventory">Get Order Inventory</button>
  </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue';
import axios from 'axios';


const message = ref<string>('');

const fetchInventory = async () => {
  try {
    const response = await axios.get('/store/inventory');
    message.value = response.data;
  } catch (error) {
    message.value = error.message;
  }
};
</script>

<style scoped>

div {
  margin-bottom: 16px;
}

button {
  padding: 8px 16px;
}
</style>
