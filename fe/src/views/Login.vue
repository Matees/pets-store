<template>
  <div>
    RESULT: {{message}}

    <h2>Login Form</h2>
    <div>
      <label for="username">Username:</label>
      <input type="text" id="username" v-model="username" />
    </div>
    <div>
      <label for="username">Password:</label>
      <input type="password" id="username" v-model="password" />
    </div>
    <button @click="login">Login</button>
  </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue';
import axios from 'axios';

const message = ref<string>('');
const username = ref<string>('');
const password = ref<string>('');

const login = async () => {
  try {
    const response = await axios.get('/user/login?username=' + username.value + '&password=' + password.value);
    message.value = response.data;
  } catch (error) {
    message.value = error.message;
  }
};
</script>

<style scoped>
/* Add your styles here */
form {
  max-width: 600px;
  margin: 0 auto;
}

div {
  margin-bottom: 16px;
}

label {
  display: block;
  margin-bottom: 4px;
}

input, select {
  width: 100%;
  padding: 8px;
  box-sizing: border-box;
}

button {
  padding: 8px 16px;
}
</style>
