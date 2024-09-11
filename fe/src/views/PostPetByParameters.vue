<template>
  <div>
    RESULT: {{message}}
    <div>
      <label for="id">ID:</label>
      <input type="number" id="id" v-model.number="pet.id" />
    </div>

    <div>
      <label for="name">Name:</label>
      <input type="text" id="name" v-model="pet.name" />
    </div>

    <div>
      <label for="status">Status:</label>
      <select id="status" v-model="pet.status">
        <option value="available">Available</option>
        <option value="pending">Pending</option>
        <option value="sold">Sold</option>
      </select>
    </div>
    <button @click="fetchPetData">Fetch Pet Data</button>
  </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue';
import axios from 'axios';
import { useNotification } from "@kyvg/vue3-notification";

const { notify }  = useNotification()

// Define the types for pet data
interface Tag {
  id: number;
  name: string;
}


interface Pet {
  id: number | null;
  name: string;
  category: Category;
  photoUrls: string[];
  tags: Tag[];
  status: string;
}

// Define the pet data model
const pet = ref<Pet>({
  id: null,
  name: '',
  category: { id: null, name: '' },
  photoUrls: [],
  tags: [],
  status: 'available',
});

const message = ref<string>('');

const fetchPetData = async () => {
  try {
    const response = await axios.post('/pet/' + pet.value.id + '/' + '?name=' + pet.value.name + '&status=' + pet.value.status);
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
