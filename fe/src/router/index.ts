import { createRouter, createWebHistory } from 'vue-router'
import PostPet from '../views/PostPet.vue'
import GetPet from '../views/GetPet.vue'
import PutPet from '../views/PutPet.vue'
import GetPetByStatus from '../views/GetPetByStatus.vue'
import GetPetByTags from '../views/GetPetByTags.vue'
import PostPetByParameters from '../views/PostPetByParameters.vue'
import DeletePet from '../views/DeletePet.vue'
import PostPetImage from '../views/PostPetImage.vue'
import StoreInventory from '../views/StoreInventory.vue'
import PostOrder from '../views/PostOrder.vue'
import GetOrder from '../views/GetOrder.vue'
import DeleteOrder from '../views/DeleteOrder.vue'
import PostUser from "@/views/PostUser.vue";
import PostUsers from "@/views/PostUsers.vue";
import Login from "@/views/Login.vue";
import Logout from "@/views/Logout.vue";
import GetUser from "@/views/GetUser.vue";
import PutUser from "@/views/PutUser.vue";
import DeleteUser from "@/views/DeleteUser.vue";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'postPet',
      component: PostPet
    },
    {
      path: '/get-pet',
      name: 'getPet',
      component: GetPet
    },
    {
      path: '/put-pet',
      name: 'putPet',
      component: PutPet
    },
    {
      path: '/get-pet-by-status',
      name: 'getPetByStatus',
      component: GetPetByStatus
    },
    {
      path: '/get-pet-by-tags',
      name: 'getPetByTags',
      component: GetPetByTags
    },
    {
      path: '/post-pet-by-parameters',
      name: 'postPetByParameters',
      component: PostPetByParameters
    },
    {
      path: '/delete-pet',
      name: 'getPetByParameters',
      component: DeletePet
    },
    {
      path: '/upload-image-pet',
      name: 'uploadImagePet',
      component: PostPetImage
    },
    {
      path: '/get-inventory',
      name: 'getInventory',
      component: StoreInventory
    },
    {
      path: '/post-order',
      name: 'PostOrder',
      component: PostOrder
    },
    {
      path: '/get-order',
      name: 'getOrder',
      component: GetOrder
    },
    {
      path: '/delete-order',
      name: 'deleteOrder',
      component: DeleteOrder
    },
    {
      path: '/create-user',
      name: 'createUser',
      component: PostUser
    },
    {
      path: '/create-users',
      name: 'createUsers',
      component: PostUsers
    },
    {
      path: '/login',
      name: 'login',
      component: Login
    },
    {
      path: '/logout',
      name: 'logout',
      component: Logout
    },
    {
      path: '/get-user',
      name: 'getUser',
      component: GetUser
    },
    {
      path: '/update-user',
      name: 'updateUser',
      component: PutUser
    },
    {
      path: '/delete-user',
      name: 'deleteUser',
      component: DeleteUser
    }
  ]
})

export default router
