import { createRouter, createWebHistory } from 'vue-router'
import TodoList from '../components/TodoList.vue'
import TaskManagement from '../components/TaskManagement.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'todo',
      component: TodoList
    },
    {
      path: '/gestion-des-taches',
      name: 'task-management',
      component: TaskManagement
    }
  ]
})

export default router