<script setup lang="ts">
</script>

<template>
  <div id="app">
    <nav class="navigation">
      <router-link to="/" class="nav-link">Todo List</router-link>
      <router-link to="/gestion-des-taches" class="nav-link">Gestion des tâches</router-link>
    </nav>

    <main class="content">
      <router-view />
    </main>
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

.navigation {
  text-align: center;
  margin-bottom: 30px;
}

.nav-link {
  display: inline-block;
  padding: 12px 24px;
  margin: 0 10px;
  background: white;
  color: #2c3e50;
  text-decoration: none;
  border-radius: 25px;
  font-weight: 500;
  transition: all 0.3s;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.nav-link:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.nav-link.router-link-active {
  background: #3498db;
  color: white;
}

.content {
  margin-top: 20px;
}
</style>
