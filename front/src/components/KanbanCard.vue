<script setup lang="ts">
import type { KanbanTask } from '../types/KanbanTask'

interface KanbanCardProps {
  task: KanbanTask
}

interface KanbanCardEmits {
  delete: [taskId: number]
  dragStart: [taskId: number]
}

const props = defineProps<KanbanCardProps>()
const emit = defineEmits<KanbanCardEmits>()

function handleDragStart(event: DragEvent) {
  if (event.dataTransfer) {
    event.dataTransfer.setData('text/plain', props.task.id.toString())
    event.dataTransfer.effectAllowed = 'move'
    emit('dragStart', props.task.id)
  }
}

</script>

<template>
  <div
    class="kanban-card"
    draggable="true"
    @dragstart="handleDragStart"
  >
    <div class="card-header">
      <h3 class="card-title">{{ task.title }}</h3>
      <button
        @click="$emit('delete', task.id)"
        class="delete-btn"
        aria-label="Supprimer la tâche"
      >
        ×
      </button>
    </div>

    <p v-if="task.description" class="card-description">
      {{ task.description }}
    </p>

    <div class="card-footer">
      <span class="status-badge" :class="task.status">
        {{ task.status === 'todo' ? 'À faire' : task.status === 'in-progress' ? 'En cours' : 'Terminé' }}
      </span>
      <div class="drag-indicator">
        ⋮⋮
      </div>
    </div>
  </div>
</template>

<style scoped>
.kanban-card {
  background: white;
  border-radius: 8px;
  padding: 16px;
  margin-bottom: 12px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  transition: transform 0.2s, box-shadow 0.2s;
  cursor: grab;
}

.kanban-card:active {
  cursor: grabbing;
}

.kanban-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.kanban-card:hover .drag-indicator {
  opacity: 1;
}

.card-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 8px;
}

.card-title {
  font-size: 16px;
  font-weight: 600;
  color: #2c3e50;
  margin: 0;
  flex: 1;
}

.delete-btn {
  background: #e74c3c;
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
  margin-left: 8px;
}

.delete-btn:hover {
  background: #c0392b;
}

.card-description {
  color: #666;
  font-size: 14px;
  margin-bottom: 12px;
  line-height: 1.4;
}

.card-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 8px;
}

.status-badge {
  padding: 4px 12px;
  border-radius: 12px;
  font-size: 12px;
  font-weight: 500;
  flex: 1;
  text-align: center;
}

.status-badge.todo {
  background: #e8f4fd;
  color: #2980b9;
}

.status-badge.in-progress {
  background: #fff3cd;
  color: #856404;
}

.status-badge.done {
  background: #d4edda;
  color: #155724;
}

.drag-indicator {
  color: #bdc3c7;
  font-size: 16px;
  font-weight: bold;
  opacity: 0.5;
  transition: opacity 0.2s;
  cursor: grab;
  user-select: none;
}
</style>
