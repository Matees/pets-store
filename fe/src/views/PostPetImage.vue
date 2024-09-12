<template>
  <div>
    RESULT: {{message}}
    <div>
      <label for="id">ID:</label>
      <input type="number" id="id" v-model.number="pet.id" />
    </div>
    <div>
      <input type="file" @change="onFileChange" accept="image/*" />

      <div v-if="imageUrl">
        <h4>Selected Image:</h4>
        <img :src="imageUrl" alt="Uploaded Image" style="max-width: 300px; margin-top: 10px;" />
      </div>
    </div>
    <button @click="uploadImage">Upload Pet Image</button>
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

const message = ref<string | null>(null);
const imageFile = ref<File | null>(null);
const imageUrl = ref<string | null>(null);

const onFileChange = (event: Event) => {
  const input = event.target as HTMLInputElement;
  const file = input.files?.[0];
  if (file) {
    imageFile.value = file;
    imageUrl.value = URL.createObjectURL(file);
    addPhotoUrl(imageUrl.value);
  }
};

const addPhotoUrl = (url: string) => {
  pet.value.photoUrls.push(url);
};

const uploadImage = async () => {
  try {
    if (pet.value.photoUrls.length != 0 && pet.value.id) {
      const formData = new FormData();
      formData.append('image', imageFile.value);

      const response = await axios.post(`/pet/${pet.value.id}/uploadImage`, formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      });
      message.value = response.data;
    } else {
      message.value = 'Please upload image and type id.'
    }
  } catch (error) {message.value = error.message;

  }
};
</script>

<style scoped>
img {
  border: 1px solid #ddd;
  border-radius: 4px;
  padding: 5px;
}

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
