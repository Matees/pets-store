<template>
  <div>
    RESULT: {{message}}

    <h2>Order Form</h2>
      <div>
        <label for="id">ID:</label>
        <input type="number" id="id" v-model.number="order.id" />
      </div>

    <button @click="deleteOrder">Delete Order</button>
  </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue';
import axios from 'axios';

interface Order {
  id: number | null;
  petId: number | null;
  quantity: number;
  shipDate: string;
  status: string;
  complete: boolean;
}

// Define the pet data model
const order = ref<Order>({
  id: null,
  petId: null,
  quantity: 0,
  shipDate: '',
  status: 'placed',
  complete: 'false',
});

const message = ref<string>('');

const deleteOrder = async () => {
  try {
    if (order.value.id){
      const response = await axios.delete('/store/order/' + order.value.id);
      message.value = response.data;
    } else {
      message.value = 'Id must not be empty'
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
