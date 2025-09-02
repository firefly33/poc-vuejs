import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import type { KanbanTask, KanbanColumn } from '../types/KanbanTask'

export const useKanbanStore = defineStore('kanban', () => {
  const tasks = ref<KanbanTask[]>([])
  const nextId = ref(1)

  const todoTasks = computed(() => tasks.value.filter(task => task.status === 'todo'))
  const inProgressTasks = computed(() => tasks.value.filter(task => task.status === 'in-progress'))
  const doneTasks = computed(() => tasks.value.filter(task => task.status === 'done'))

  function addTask(title: string, description?: string) {
    const newTask: KanbanTask = {
      id: nextId.value++,
      title,
      description,
      status: 'todo',
      createdAt: new Date()
    }
    tasks.value.push(newTask)
    saveTasks()
  }

  function moveTask(taskId: number, direction: 'left' | 'right') {
    const task = tasks.value.find(t => t.id === taskId)
    if (!task) return

    const statusOrder: KanbanColumn[] = ['todo', 'in-progress', 'done']
    const currentIndex = statusOrder.indexOf(task.status)

    if (direction === 'right' && currentIndex < statusOrder.length - 1) {
      task.status = statusOrder[currentIndex + 1]
    } else if (direction === 'left' && currentIndex > 0) {
      task.status = statusOrder[currentIndex - 1]
    }

    saveTasks()
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
      saveTasks()
    }
  }

  function saveTasks() {
    localStorage.setItem('kanban-tasks', JSON.stringify(tasks.value))
  }

  function loadTasks() {
    const stored = localStorage.getItem('kanban-tasks')
    if (stored) {
      const parsedTasks = JSON.parse(stored)
      tasks.value = parsedTasks.map((task: any) => ({
        ...task,
        createdAt: new Date(task.createdAt)
      }))
      
      if (tasks.value.length > 0) {
        nextId.value = Math.max(...tasks.value.map(t => t.id)) + 1
      }
    } else {
      // Add some sample data
      addTask('Première tâche', 'Ceci est un exemple de tâche')
      addTask('Tâche en cours', 'Une tâche qui est actuellement en développement')
      addTask('Tâche terminée', 'Une tâche qui a été complétée')
      
      // Move sample tasks to different columns
      if (tasks.value.length >= 2) {
        tasks.value[1].status = 'in-progress'
      }
      if (tasks.value.length >= 3) {
        tasks.value[2].status = 'done'
      }
      saveTasks()
    }
  }

  return {
    tasks,
    todoTasks,
    inProgressTasks,
    doneTasks,
    addTask,
    moveTask,
    moveTaskToColumn,
    deleteTask,
    loadTasks
  }
})