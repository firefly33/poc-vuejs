import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import type { KanbanTask, KanbanColumn, ApiResponse } from '../types/KanbanTask'

const API_BASE_URL = 'http://localhost/api'

export const useKanbanStore = defineStore('kanban', () => {
  const tasks = ref<KanbanTask[]>([])
  const isLoading = ref(false)
  const error = ref<string | null>(null)

  const todoTasks = computed(() => tasks.value.filter(task => task.status === 'todo'))
  const inProgressTasks = computed(() => tasks.value.filter(task => task.status === 'in-progress'))
  const doneTasks = computed(() => tasks.value.filter(task => task.status === 'done'))

  async function fetchTasks() {
    isLoading.value = true
    error.value = null
    
    try {
      const response = await fetch(`${API_BASE_URL}/tasks`)
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`)
      }
      
      const apiResponse: ApiResponse = await response.json()
      if (apiResponse.success) {
        tasks.value = apiResponse.data.sort((a, b) => 
          new Date(b.created_at).getTime() - new Date(a.created_at).getTime()
        )
      } else {
        throw new Error(apiResponse.message || 'Failed to fetch tasks')
      }
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Failed to fetch tasks'
      console.error('Error fetching tasks:', err)
      // Fallback to localStorage if API fails
      loadTasksFromStorage()
    } finally {
      isLoading.value = false
    }
  }

  function addTask(title: string, description?: string) {
    // For now, add locally. TODO: Implement POST API call
    const newTask: KanbanTask = {
      id: Math.max(0, ...tasks.value.map(t => t.id)) + 1,
      title,
      description,
      status: 'todo',
      created_at: new Date().toISOString(),
      updated_at: new Date().toISOString()
    }
    tasks.value.unshift(newTask) // Add to beginning since we sort by newest first
    saveTasksToStorage()
  }

  function moveTask(taskId: number, direction: 'left' | 'right') {
    const task = tasks.value.find(t => t.id === taskId)
    if (!task) return

    const statusOrder: KanbanColumn[] = ['todo', 'in-progress', 'done']
    const currentIndex = statusOrder.indexOf(task.status)

    if (direction === 'right' && currentIndex < statusOrder.length - 1) {
      task.status = statusOrder[currentIndex + 1]
      task.updated_at = new Date().toISOString()
    } else if (direction === 'left' && currentIndex > 0) {
      task.status = statusOrder[currentIndex - 1]
      task.updated_at = new Date().toISOString()
    }

    // TODO: Implement PUT API call
    saveTasksToStorage()
  }

  function moveTaskToColumn(taskId: number, newStatus: KanbanColumn) {
    const task = tasks.value.find(t => t.id === taskId)
    if (!task) return

    task.status = newStatus
    saveTasks()
  }

  function deleteTask(taskId: number) {
    const index = tasks.value.findIndex(t => t.id === taskId)
    if (index > -1) {
      tasks.value.splice(index, 1)
      // TODO: Implement DELETE API call
      saveTasksToStorage()
    }
  }

  function saveTasksToStorage() {
    localStorage.setItem('kanban-tasks', JSON.stringify(tasks.value))
  }

  function loadTasksFromStorage() {
    const stored = localStorage.getItem('kanban-tasks')
    if (stored) {
      tasks.value = JSON.parse(stored)
    }
  }

  async function loadTasks() {
    // Try to fetch from API first, fallback to localStorage on error
    await fetchTasks()
  }

  return {
    tasks,
    isLoading,
    error,
    todoTasks,
    inProgressTasks,
    doneTasks,
    addTask,
    moveTask,
    moveTaskToColumn,
    deleteTask,
    loadTasks,
    fetchTasks
  }
})