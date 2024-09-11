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
    }
  ]
})

export default router
