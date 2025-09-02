<script setup lang="ts">
import KanbanBoard from './KanbanBoard.vue'
</script>

<template>
  <div class="task-management">
    <header class="page-header">
      <h1>Gestion des tâches</h1>
      <p>Organisez vos tâches avec notre tableau Kanban</p>
    </header>
    
    <KanbanBoard />
  </div>
</template>

<style scoped>
.task-management {
  min-height: 100vh;
}

.page-header {
  text-align: center;
  margin-bottom: 30px;
}

.page-header h1 {
  font-size: 2.5rem;
  color: #2c3e50;
  margin-bottom: 10px;
}

.page-header p {
  color: #666;
  font-size: 1.1rem;
  margin: 0;
}
</style>