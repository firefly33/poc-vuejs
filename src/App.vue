<script setup lang="ts">
import TodoList from './components/TodoList.vue'
</script>

<template>
  <div id="app">
    <TodoList />
  </div>
</template>

<style>
* {
  box-sizing: border-box;
}

body {
  margin: 0;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', sans-serif;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  min-height: 100vh;
}

#app {
  padding: 20px;
  min-height: 100vh;
}
</style>
