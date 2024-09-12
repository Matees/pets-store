<template>
  <div>
    RESULT: {{message}}
    <div>
      <label for="id">ID:</label>
      <input type="number" id="id" v-model.number="pet.id" />
    </div>
    <button @click="fetchPetData">Delete Pet</button>
  </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue';
import axios from 'axios';

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
    if (pet.value.id) {
      const response = await axios.delete('/pet/delete/' + pet.value.id);
      message.value = response.data;
    } else {
      message.value = 'Please enter id.'
    }
  } catch (error) {message.value = error.message;

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
