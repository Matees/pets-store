<template>
  <div>
    RESULT: {{message}}
    <div>
      <label for="tags">Tags (Press Enter):</label>
      <input type="text" id="tagInput" v-model="tagInput" @keyup.enter="addTag" />
      <ul>
        <li v-for="(tag, index) in pet.tags" :key="index">{{ tag.name }}</li>
      </ul>
    </div>

    <div  v-if="pet.photoUrls.length > 0">
      <div v-for="(url, index) in pet.photoUrls" :key="index" style="margin-bottom: 10px;">
        <img :src="url" style="max-width: 300px; margin-top: 10px;" />
      </div>
    </div>
    <button @click="fetchPetData">Fetch Pet Data</button>
  </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue';
import axios from 'axios';

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

function addTag() {
  if (tagInput.value.trim()) {
    pet.value.tags.push({ id: pet.value.tags.length, name: tagInput.value.trim() });
    tagInput.value = '';
  }
}

function addPhotoUrl(url) {
  if (url.trim()) {
    pet.value.photoUrls.push(url.trim());
  }
}

const fetchPetData = async () => {
  try {
    if (pet.value.tags) {
      const formattedTags = pet.value.tags.map(tag => `tags=${encodeURIComponent(tag.name)}`);

      const tags = formattedTags.join('&');
      const response = await axios.get('/pet/findByTags?' + tags );
      message.value = response.data;
      if (response.data.message.photoUrls) {
        response.data.message.photoUrls.forEach((url, index) => {
          addPhotoUrl(url);
        });
      }
    } else {
      message.value = 'Please choose tags.'
    }
  } catch (error) {
    console.log(error)
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
