<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useKanbanStore } from '../stores/kanban'
import KanbanCard from './KanbanCard.vue'
import type { KanbanColumn } from '../types/KanbanTask'

const kanbanStore = useKanbanStore()
const newTaskTitle = ref('')
const newTaskDescription = ref('')
const draggedTaskId = ref<number | null>(null)

function addTask() {
  const title = newTaskTitle.value.trim()
  if (!title) return

  kanbanStore.addTask(title, newTaskDescription.value.trim() || undefined)
  newTaskTitle.value = ''
  newTaskDescription.value = ''
}

function deleteTask(taskId: number) {
  kanbanStore.deleteTask(taskId)
}

function handleDragStart(taskId: number) {
  draggedTaskId.value = taskId
}

function handleDragOver(event: DragEvent) {
  event.preventDefault()
  event.dataTransfer!.dropEffect = 'move'
}

function handleDrop(event: DragEvent, newStatus: KanbanColumn) {
  event.preventDefault()

  const taskId = event.dataTransfer?.getData('text/plain')
  if (taskId && draggedTaskId.value) {
    kanbanStore.moveTaskToColumn(parseInt(taskId), newStatus)
    draggedTaskId.value = null
  }
}

function handleDragEnter(event: DragEvent) {
  event.preventDefault()
  const target = event.currentTarget as HTMLElement
  target.classList.add('drag-over')
}

function handleDragLeave(event: DragEvent) {
  const target = event.currentTarget as HTMLElement
  // Only remove class if we're actually leaving the drop zone
  if (!target.contains(event.relatedTarget as Node)) {
    target.classList.remove('drag-over')
  }
}

onMounted(() => {
  kanbanStore.loadTasks()
})
</script>

<template>
  <div class="kanban-board">
    <!-- Loading State -->
    <div v-if="kanbanStore.isLoading" class="loading-state">
      <p>Chargement des tâches...</p>
    </div>

    <!-- Error State -->
    <div v-if="kanbanStore.error" class="error-state">
      <p>Erreur: {{ kanbanStore.error }}</p>
      <button @click="kanbanStore.fetchTasks" class="retry-btn">Réessayer</button>
    </div>

    <!-- Main Content -->
    <template v-if="!kanbanStore.isLoading">
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
        <div
          class="column-content drop-zone"
          @dragover="handleDragOver"
          @drop="handleDrop($event, 'todo')"
          @dragenter="handleDragEnter"
          @dragleave="handleDragLeave"
        >
          <KanbanCard
            v-for="task in kanbanStore.todoTasks"
            :key="task.id"
            :task="task"
            @drag-start="handleDragStart"
            @delete="deleteTask"
          />
          <div v-if="kanbanStore.todoTasks.length === 0" class="empty-column">
            Glissez une tâche ici
          </div>
        </div>
      </div>

      <div class="column">
        <div class="column-header in-progress">
          <h3>En cours</h3>
          <span class="task-count">{{ kanbanStore.inProgressTasks.length }}</span>
        </div>
        <div
          class="column-content drop-zone"
          @dragover="handleDragOver"
          @drop="handleDrop($event, 'in-progress')"
          @dragenter="handleDragEnter"
          @dragleave="handleDragLeave"
        >
          <KanbanCard
            v-for="task in kanbanStore.inProgressTasks"
            :key="task.id"
            :task="task"
            @drag-start="handleDragStart"
            @delete="deleteTask"
          />
          <div v-if="kanbanStore.inProgressTasks.length === 0" class="empty-column">
            Glissez une tâche ici
          </div>
        </div>
      </div>

      <div class="column">
        <div class="column-header done">
          <h3>Terminé</h3>
          <span class="task-count">{{ kanbanStore.doneTasks.length }}</span>
        </div>
        <div
          class="column-content drop-zone"
          @dragover="handleDragOver"
          @drop="handleDrop($event, 'done')"
          @dragenter="handleDragEnter"
          @dragleave="handleDragLeave"
        >
          <KanbanCard
            v-for="task in kanbanStore.doneTasks"
            :key="task.id"
            :task="task"
            @drag-start="handleDragStart"
            @delete="deleteTask"
          />
          <div v-if="kanbanStore.doneTasks.length === 0" class="empty-column">
            Glissez une tâche ici
          </div>
        </div>
      </div>
    </div>
    </template>
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
  min-height: 300px;
  transition: all 0.3s ease;
}

.drop-zone {
  position: relative;
}

.drop-zone.drag-over {
  background-color: rgba(52, 152, 219, 0.1);
  border: 2px dashed #3498db;
  border-radius: 8px;
}

.drop-zone.drag-over::before {
  content: 'Déposez la tâche ici';
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  background: #3498db;
  color: white;
  padding: 8px 16px;
  border-radius: 4px;
  font-weight: 500;
  pointer-events: none;
  z-index: 10;
}

.empty-column {
  text-align: center;
  color: #666;
  font-style: italic;
  padding: 40px 20px;
}

.loading-state,
.error-state {
  text-align: center;
  padding: 40px;
  background: white;
  border-radius: 12px;
  margin-bottom: 20px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.loading-state p {
  color: #666;
  font-size: 18px;
}

.error-state {
  background: #fee;
  border: 1px solid #fcc;
}

.error-state p {
  color: #c33;
  margin-bottom: 15px;
}

.retry-btn {
  padding: 10px 20px;
  background: #e74c3c;
  color: white;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-size: 14px;
}

.retry-btn:hover {
  background: #c0392b;
}
</style>
