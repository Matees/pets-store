<template>
  <div>
    RESULT: {{message}}

    <div>
      <label for="username">Username to find:</label>
      <input type="text" id="username" v-model="username" />
    </div>

    <h2>User Form</h2>
    <div>
      <label for="id">ID:</label>
      <input type="number" id="id" v-model.number="user.id" />
    </div>

    <div>
      <label for="username">Username:</label>
      <input type="text" id="username" v-model="user.username" />
    </div>

    <div>
      <label for="firstName">First Name:</label>
      <input type="text" id="firstName" v-model="user.firstName" />
    </div>

    <div>
      <label for="lastName">Last Name:</label>
      <input type="text" id="lastName" v-model="user.lastName" />
    </div>

    <div>
      <label for="email">Email:</label>
      <input type="email" id="email" v-model="user.email" />
    </div>

    <div>
      <label for="password">Password:</label>
      <input type="password" id="password" v-model="user.password" />
    </div>

    <div>
      <label for="phone">Phone:</label>
      <input type="text" id="phone" v-model="user.phone" />
    </div>

    <div>
      <label for="userStatus">User Status:</label>
      <select id="userStatus" v-model.number="user.userStatus">
        <option value="0">Inactive</option>
        <option value="1">Active</option>
      </select>
    </div>

    <button @click="postUser">Send</button>
    <button @click="clearForm">Clear Form</button>
  </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue';
import axios from 'axios';


interface User {
  id: number | null;
  username: string;
  firstName: string;
  lastName: string;
  email: string;
  password: string;
  phone: string;
  userStatus: number;
}

const user = ref<User>({
  id: null,
  username: '',
  firstName: '',
  lastName: '',
  email: '',
  password: '',
  phone: '',
  userStatus: 1,
});

const message = ref<string>('');
const username = ref<string>('');

const postUser = async () => {
  try {
    if (username.value){
      const response = await axios.put('/user/update/' + username.value, user.value);
      message.value = response.data;
    } else {
      message.value = 'Username must not be empty.'
    }
  } catch (error) {
    message.value = error.message;
  }
};

// Clear form data
const clearForm = () => {
  user.value = {
    id: null,
    username: '',
    firstName: '',
    lastName: '',
    email: '',
    password: '',
    phone: '',
    userStatus: 1,
  };
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
