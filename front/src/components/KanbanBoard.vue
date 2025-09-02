<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useKanbanStore } from '../stores/kanban'
import KanbanCard from './KanbanCard.vue'

const kanbanStore = useKanbanStore()
const newTaskTitle = ref('')
const newTaskDescription = ref('')

function addTask() {
  const title = newTaskTitle.value.trim()
  if (!title) return

  kanbanStore.addTask(title, newTaskDescription.value.trim() || undefined)
  newTaskTitle.value = ''
  newTaskDescription.value = ''
}

function moveTaskLeft(taskId: number) {
  kanbanStore.moveTask(taskId, 'left')
}

function moveTaskRight(taskId: number) {
  kanbanStore.moveTask(taskId, 'right')
}

function deleteTask(taskId: number) {
  kanbanStore.deleteTask(taskId)
}

onMounted(() => {
  kanbanStore.loadTasks()
})
</script>

<template>
  <div class="kanban-board">
    <div class="add-task-section">
      <h2>Ajouter une nouvelle tâche</h2>
      <form @submit.prevent="addTask" class="add-task-form">
        <input
          v-model="newTaskTitle"
          type="text"
          placeholder="Titre de la tâche..."
          class="task-input"
          required
        />
        <textarea
          v-model="newTaskDescription"
          placeholder="Description (optionnelle)..."
          class="task-textarea"
          rows="2"
        ></textarea>
        <button type="submit" class="add-btn">Ajouter la tâche</button>
      </form>
    </div>

    <div class="board-columns">
      <div class="column">
        <div class="column-header todo">
          <h3>À faire</h3>
          <span class="task-count">{{ kanbanStore.todoTasks.length }}</span>
        </div>
        <div class="column-content">
          <KanbanCard
            v-for="task in kanbanStore.todoTasks"
            :key="task.id"
            :task="task"
            @move-left="moveTaskLeft"
            @move-right="moveTaskRight"
            @delete="deleteTask"
          />
          <div v-if="kanbanStore.todoTasks.length === 0" class="empty-column">
            Aucune tâche à faire
          </div>
        </div>
      </div>

      <div class="column">
        <div class="column-header in-progress">
          <h3>En cours</h3>
          <span class="task-count">{{ kanbanStore.inProgressTasks.length }}</span>
        </div>
        <div class="column-content">
          <KanbanCard
            v-for="task in kanbanStore.inProgressTasks"
            :key="task.id"
            :task="task"
            @move-left="moveTaskLeft"
            @move-right="moveTaskRight"
            @delete="deleteTask"
          />
          <div v-if="kanbanStore.inProgressTasks.length === 0" class="empty-column">
            Aucune tâche en cours
          </div>
        </div>
      </div>

      <div class="column">
        <div class="column-header done">
          <h3>Terminé</h3>
          <span class="task-count">{{ kanbanStore.doneTasks.length }}</span>
        </div>
        <div class="column-content">
          <KanbanCard
            v-for="task in kanbanStore.doneTasks"
            :key="task.id"
            :task="task"
            @move-left="moveTaskLeft"
            @move-right="moveTaskRight"
            @delete="deleteTask"
          />
          <div v-if="kanbanStore.doneTasks.length === 0" class="empty-column">
            Aucune tâche terminée
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.kanban-board {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
}

.add-task-section {
  background: white;
  border-radius: 12px;
  padding: 24px;
  margin-bottom: 30px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.add-task-section h2 {
  color: #2c3e50;
  margin-bottom: 20px;
  font-size: 1.5rem;
}

.add-task-form {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.task-input,
.task-textarea {
  padding: 12px;
  border: 2px solid #ddd;
  border-radius: 8px;
  font-size: 16px;
  font-family: inherit;
  transition: border-color 0.2s;
}

.task-input:focus,
.task-textarea:focus {
  outline: none;
  border-color: #3498db;
}

.task-textarea {
  resize: vertical;
  min-height: 60px;
}

.add-btn {
  padding: 14px 28px;
  background: #3498db;
  color: white;
  border: none;
  border-radius: 8px;
  font-size: 16px;
  font-weight: 500;
  cursor: pointer;
  transition: background 0.2s;
  align-self: flex-start;
}

.add-btn:hover {
  background: #2980b9;
}

.board-columns {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 24px;
}

@media (max-width: 768px) {
  .board-columns {
    grid-template-columns: 1fr;
  }
}

.column {
  background: #f8f9fa;
  border-radius: 12px;
  min-height: 400px;
}

.column-header {
  padding: 16px 20px;
  border-radius: 12px 12px 0 0;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.column-header.todo {
  background: #e8f4fd;
  color: #2980b9;
}

.column-header.in-progress {
  background: #fff3cd;
  color: #856404;
}

.column-header.done {
  background: #d4edda;
  color: #155724;
}

.column-header h3 {
  margin: 0;
  font-size: 18px;
  font-weight: 600;
}

.task-count {
  background: rgba(255, 255, 255, 0.8);
  padding: 4px 12px;
  border-radius: 12px;
  font-size: 14px;
  font-weight: 500;
}

.column-content {
  padding: 20px;
}

.empty-column {
  text-align: center;
  color: #666;
  font-style: italic;
  padding: 40px 20px;
}
</style>