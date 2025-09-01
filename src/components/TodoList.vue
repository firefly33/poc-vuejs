<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import TodoItem from './TodoItem.vue'
import type { Todo } from '../types/Todo'

const todos = ref<Todo[]>([])
const newTodoText = ref('')
const filter = ref<'all' | 'active' | 'completed'>('all')
const nextId = ref(1)

const filteredTodos = computed(() => {
  switch (filter.value) {
    case 'active':
      return todos.value.filter(todo => !todo.completed)
    case 'completed':
      return todos.value.filter(todo => todo.completed)
    default:
      return todos.value
  }
})

const remainingCount = computed(() => {
  return todos.value.filter(todo => !todo.completed).length
})

function addTodo() {
  const text = newTodoText.value.trim()
  if (!text) return

  const newTodo: Todo = {
    id: nextId.value++,
    text,
    completed: false,
    createdAt: new Date()
  }

  todos.value.push(newTodo)
  newTodoText.value = ''
  saveTodos()
}

function toggleTodo(id: number) {
  const todo = todos.value.find(t => t.id === id)
  if (todo) {
    todo.completed = !todo.completed
    saveTodos()
  }
}

function deleteTodo(id: number) {
  const index = todos.value.findIndex(t => t.id === id)
  if (index > -1) {
    todos.value.splice(index, 1)
    saveTodos()
  }
}

function saveTodos() {
  localStorage.setItem('todos', JSON.stringify(todos.value))
}

function loadTodos() {
  const stored = localStorage.getItem('todos')
  if (stored) {
    const parsedTodos = JSON.parse(stored)
    todos.value = parsedTodos.map((todo: any) => ({
      ...todo,
      createdAt: new Date(todo.createdAt)
    }))
    
    if (todos.value.length > 0) {
      nextId.value = Math.max(...todos.value.map(t => t.id)) + 1
    }
  }
}

onMounted(() => {
  loadTodos()
})
</script>

<template>
  <div class="todo-app">
    <header class="header">
      <h1>Todo List</h1>
      <form @submit.prevent="addTodo" class="add-todo-form">
        <input
          v-model="newTodoText"
          type="text"
          placeholder="Ajouter une nouvelle tâche..."
          class="todo-input"
        />
        <button type="submit" class="add-btn">Ajouter</button>
      </form>
    </header>

    <main class="main" v-if="todos.length > 0">
      <div class="filters">
        <button
          @click="filter = 'all'"
          :class="{ active: filter === 'all' }"
          class="filter-btn"
        >
          Toutes ({{ todos.length }})
        </button>
        <button
          @click="filter = 'active'"
          :class="{ active: filter === 'active' }"
          class="filter-btn"
        >
          Actives ({{ remainingCount }})
        </button>
        <button
          @click="filter = 'completed'"
          :class="{ active: filter === 'completed' }"
          class="filter-btn"
        >
          Terminées ({{ todos.length - remainingCount }})
        </button>
      </div>

      <ul class="todo-list">
        <TodoItem
          v-for="todo in filteredTodos"
          :key="todo.id"
          :todo="todo"
          @toggle="toggleTodo"
          @delete="deleteTodo"
        />
      </ul>

      <footer class="footer">
        <span class="todo-count">
          {{ remainingCount }} tâche{{ remainingCount !== 1 ? 's' : '' }} restante{{ remainingCount !== 1 ? 's' : '' }}
        </span>
      </footer>
    </main>

    <div v-else class="empty-state">
      <p>Aucune tâche pour le moment. Ajoutez-en une ci-dessus !</p>
    </div>
  </div>
</template>

<style scoped>
.todo-app {
  max-width: 600px;
  margin: 0 auto;
  padding: 20px;
}

.header {
  text-align: center;
  margin-bottom: 30px;
}

.header h1 {
  font-size: 2.5rem;
  color: #2c3e50;
  margin-bottom: 20px;
}

.add-todo-form {
  display: flex;
  gap: 10px;
  margin-bottom: 20px;
}

.todo-input {
  flex: 1;
  padding: 12px;
  border: 2px solid #ddd;
  border-radius: 6px;
  font-size: 16px;
}

.todo-input:focus {
  outline: none;
  border-color: #3498db;
}

.add-btn {
  padding: 12px 20px;
  background: #3498db;
  color: white;
  border: none;
  border-radius: 6px;
  font-size: 16px;
  cursor: pointer;
}

.add-btn:hover {
  background: #2980b9;
}

.filters {
  display: flex;
  justify-content: center;
  gap: 10px;
  margin-bottom: 20px;
}

.filter-btn {
  padding: 8px 16px;
  border: 2px solid #ddd;
  background: white;
  border-radius: 20px;
  cursor: pointer;
  transition: all 0.2s;
}

.filter-btn.active {
  background: #3498db;
  color: white;
  border-color: #3498db;
}

.filter-btn:hover {
  border-color: #3498db;
}

.todo-list {
  list-style: none;
  padding: 0;
  margin: 0;
  background: white;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.footer {
  text-align: center;
  margin-top: 20px;
  color: #666;
}

.empty-state {
  text-align: center;
  color: #666;
  font-style: italic;
  margin-top: 40px;
}
</style>