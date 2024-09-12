<template>
  <div>
    RESULT: {{message}}

    <h2>User Form</h2>
    <div>
      <label for="username">Username:</label>
      <input type="text" id="username" v-model="username" />
    </div>

    <button @click="deleteUser">Delete</button>
  </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue';
import axios from 'axios';

const message = ref<string>('');
const username = ref<string>('');

const deleteUser = async () => {
  try {
    if (username.value){
      const response = await axios.delete('/user/delete/' + username.value);
      message.value = response.data;
    } else {
      message.value = 'Username most not be empty'
    }
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
