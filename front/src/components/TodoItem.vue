<script setup lang="ts">
import type { Todo } from '../types/Todo'

interface TodoItemProps {
  todo: Todo
}

interface TodoItemEmits {
  toggle: [id: number]
  delete: [id: number]
}

defineProps<TodoItemProps>()
defineEmits<TodoItemEmits>()
</script>

<template>
  <li class="todo-item" :class="{ completed: todo.completed }">
    <input
      type="checkbox"
      :checked="todo.completed"
      @change="$emit('toggle', todo.id)"
      class="todo-checkbox"
    />
    <span class="todo-text">{{ todo.text }}</span>
    <button
      @click="$emit('delete', todo.id)"
      class="delete-btn"
      aria-label="Supprimer la tâche"
    >
      ×
    </button>
  </li>
</template>

<style scoped>
.todo-item {
  display: flex;
  align-items: center;
  padding: 12px;
  border-bottom: 1px solid #eee;
  gap: 12px;
}

.todo-item.completed .todo-text {
  text-decoration: line-through;
  color: #999;
}

.todo-checkbox {
  width: 18px;
  height: 18px;
}

.todo-text {
  flex: 1;
  font-size: 16px;
}

.delete-btn {
  background: #ff4444;
  color: white;
  border: none;
  border-radius: 50%;
  width: 24px;
  height: 24px;
  font-size: 16px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
}

.delete-btn:hover {
  background: #cc0000;
}
</style>