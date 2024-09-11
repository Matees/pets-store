<template>
  <div>
    RESULT: {{message}}

    <h2>Order Form</h2>
<!--    <form @submit.prevent="handleSubmit">-->
      <div>
        <label for="id">ID:</label>
        <input type="number" id="id" v-model.number="order.id" />
      </div>

      <div>
        <label for="pet-id">Pet ID:</label>
        <input type="number" id="pet-id" v-model.number="order.petId" />
      </div>

      <div>
        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" v-model.number="order.quantity" />
      </div>

      <div>
        <label for="datetime">Select a date and time:</label>
        <input
            type="datetime-local"
            id="datetime"
            v-model="order.shipDate"
        />
      </div>

    <div>
      <label for="status">Status:</label>
      <select id="status" v-model="order.status">
        <option value="placed">Placed</option>
        <option value="approved">Approved</option>
        <option value="delivered">Delivered</option>
      </select>
    </div>

    <div>
      <label for="complete">Complete:</label>
      <input
          type="checkbox"
          id="complete"
          v-model="order.complete"
      />
    </div>

    <button @click="postOrder">Send</button>
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
  complete: false,
});

const message = ref<string>('');

const postOrder = async () => {
  try {
    const response = await axios.post('/store/order', order.value);
    message.value = response.data;
  } catch (error) {
    message.value = error.message;
  }
};

const clearForm = () => {
  console.log(order.value.complete)
  order.value = {
    id: null,
    petId: null,
    quantity: 0,
    shipDate: '',
    status: 'placed',
    complete: 'false',
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
