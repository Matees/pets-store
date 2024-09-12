<template>
  <div>
    RESULT: {{message}}

    <h2>Pet Form</h2>
<!--    <form @submit.prevent="handleSubmit">-->
      <div>
        <label for="id">ID:</label>
        <input type="number" id="id" v-model.number="pet.id" />
      </div>

      <div>
        <label for="name">Name:</label>
        <input type="text" id="name" v-model="pet.name" />
      </div>

      <div>
        <label for="categoryId">Category ID:</label>
        <input type="number" id="categoryId" v-model.number="pet.category.id" />
      </div>

      <div>
        <label for="categoryName">Category Name:</label>
        <input type="text" id="categoryName" v-model="pet.category.name" />
      </div>

      <div>
        <label for="photoUrls">Photo URLs (press Enter): </label>
        <input type="text" id="photoUrls" v-model="photoUrlInput" @keyup.enter="addPhotoUrl" />
        <ul>
          <li v-for="(url, index) in pet.photoUrls" :key="index">{{ url }}</li>
        </ul>
      </div>

      <div>
        <label for="tags">Tags (press Enter):</label>
        <input type="text" id="tagInput" v-model="tagInput" @keyup.enter="addTag" />
        <ul>
          <li v-for="(tag, index) in pet.tags" :key="index">{{ tag.name }}</li>
        </ul>
      </div>

      <div>
        <label for="status">Status:</label>
        <select id="status" v-model="pet.status">
          <option value="available">Available</option>
          <option value="pending">Pending</option>
          <option value="sold">Sold</option>
        </select>
      </div>

    <button @click="postPet">Send</button>
    <button @click="clearForm">Clear Form</button>
<!--    </form>-->
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

interface Category {
  id: number | null;
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

// Form input bindings
const photoUrlInput = ref('');
const tagInput = ref('');

// Methods to handle form submission and data manipulation
function addPhotoUrl() {
  if (photoUrlInput.value.trim()) {
    pet.value.photoUrls.push(photoUrlInput.value.trim());
    photoUrlInput.value = '';
  }
}

function addTag() {
  if (tagInput.value.trim()) {
    pet.value.tags.push({ id: pet.value.tags.length, name: tagInput.value.trim() });
    tagInput.value = '';
  }
}

const postPet = async () => {
  try {
    const response = await axios.post('/pet/create', pet.value);
    message.value = response.data;
  } catch (error) {
    message.value = error.message;
  }
};

const clearForm = () => {
  pet.value = {
    id: null,
    name: '',
    category: { id: null, name: '' },
    photoUrls: [],
    tags: [],
    status: 'available',
  };
  photoUrlInput.value = '';
  tagInput.value = '';
};

const fetchPetData = async () => {
  try {
    const response = await axios.get('/pet/1'); // Fetch data for a specific pet ID, adjust as necessary
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
