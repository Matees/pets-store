<template>
  <div>
    RESULT: {{message}}

    <button @click="logout">Logout</button>
  </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue';
import axios from 'axios';

const message = ref<string>('');

const logout = async () => {
  try {
    const response = await axios.get('/user/logout');
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
