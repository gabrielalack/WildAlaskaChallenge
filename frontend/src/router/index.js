import { createRouter, createWebHistory } from 'vue-router'
import RecipeList from '@/components/RecipeList.vue'
import RecipeDisplay from '@/components/RecipeDisplay.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'Recipe List',
      component: RecipeList,
    },
     {
      path: '/recipe/:slug',
      name: 'Recipe Details',
      component: RecipeDisplay,
    },
  ]
})

export default router
