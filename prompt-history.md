# Historique des prompts - Projet Vue.js Todo

## Liste des prompts utilisateur

### 1. Création du projet
**Prompt :** "Create a vuejs project with it's latest version"

**Actions réalisées :** 
- Tentative initiale avec npm create vue@latest
- Ajustement pour utiliser Vite avec template TypeScript

---

### 2. Utilisation de Vite
**Prompt :** "[Request interrupted by user for tool use]use vite to create the project"

**Actions réalisées :**
- Redirection vers l'utilisation de Vite

---

### 3. Template TypeScript
**Prompt :** "[Request interrupted by user for tool use]vite and template vue-ts"

**Actions réalisées :**
- Création du projet avec `npm create vite@latest . -- --template vue-ts`
- Installation des dépendances avec `npm install`

---

### 4. Initialisation Git
**Prompt :** "create a git project, and commit everything into the first commit"

**Actions réalisées :**
- `git init`
- `git add .`
- Commit initial avec message formaté

---

### 5. Implémentation Todo List
**Prompt :** "Start implementing a todo list page"

**Actions réalisées :**
- Analyse de la structure du projet existant
- Planification des tâches avec TodoWrite

---

### 6. Demande de journal
**Prompt :** "[Request interrupted by user for tool use]By this point, can you list all the tasks we are going to do in a journal.md file (write it in french), please proceed"

**Actions réalisées :**
- Création du fichier journal.md en français
- Liste détaillée des tâches à réaliser
- Implémentation complète de l'application todo :
  - Interface Todo TypeScript
  - Composant TodoItem
  - Composant TodoList avec filtrage et localStorage
  - Mise à jour d'App.vue

---

### 7. Mise à jour du journal
**Prompt :** "check the markdown tasks that are done"

**Actions réalisées :**
- Lecture du journal.md
- Mise à jour des tâches terminées (checkboxes cochées)
- Mise à jour des notes de progression

---

### 8. Connexion au repository distant
**Prompt :** "Plug the local repository to the remote -> "git@github.com:firefly33/poc-vuejs.git", then, i want you to push the existing first commit to main branch"

**Actions réalisées :**
- `git remote add origin git@github.com:firefly33/poc-vuejs.git`
- `git push -u origin main`

---

### 9. Création de branche et commit
**Prompt :** "now, for the current changes, I want you to switch locally to a new branch (call it todo-list), then commit the pending changes with a Conventional Commits naming, and finally, push the new branch to the repository"

**Actions réalisées :**
- `git checkout -b todo-list`
- Commit avec format Conventional Commits : "feat: implement complete todo list application"
- `git push -u origin todo-list`

---

### 10. Création de l'historique des prompts
**Prompt :** "Can you list (until now and for now on) all the prompts I gave you in a prompt-history.md file ?"

**Actions réalisées :**
- Création de ce fichier prompt-history.md
- Documentation de tous les prompts précédents avec leurs actions associées

---

### 11. Commit de l'historique et mise à jour automatique
**Prompt :** "Commit the prompt history file, and update it everytime a tell you something else"

**Actions réalisées :**
- Commit du fichier prompt-history.md avec format Conventional Commits
- Engagement de mise à jour automatique pour chaque nouveau prompt

---

### 12. Ajout d'une nouvelle page "Gestion des tâches"
**Prompt :** "Add a new page to the app named "Gestion des tâches", with only this title in it"

**Actions réalisées :**
- Installation de Vue Router 4
- Création du composant TaskManagement.vue avec le titre "Gestion des tâches"
- Configuration du routeur avec deux routes (/, /gestion-des-taches)
- Mise à jour de main.ts pour utiliser le routeur
- Modification d'App.vue pour inclure la navigation avec router-link et router-view
- Ajout de styles pour la navigation

---

### 13. Ajout d'un système Kanban avec Pinia
**Prompt :** "Great ! Now Add a Kanban feature to the new page. Please use Pinia to handle the state. For the moment, do not implement drag and drop, just add left and right arrow buttons to move the tasks"

**Actions réalisées :**
- Installation de Pinia pour la gestion d'état
- Configuration de Pinia dans main.ts
- Création de l'interface KanbanTask avec statuts (todo, in-progress, done)
- Création du store Kanban avec Pinia (ajout, déplacement, suppression, persistance localStorage)
- Création du composant KanbanCard avec boutons de déplacement gauche/droite
- Création du composant KanbanBoard avec 3 colonnes et formulaire d'ajout
- Mise à jour de TaskManagement.vue pour intégrer le tableau Kanban
- Ajout de données d'exemple lors du premier chargement

---

### 15. Restructuration en monorepo avec dossiers front et back
**Prompt :** "Now, I want you to move the VueJS inside a subfolder named "front", then, I want you to create an other subfolder called "back""

**Actions réalisées :**
- Création des dossiers front/ et back/
- Déplacement de tous les fichiers Vue.js vers le dossier front/ :
  - .vscode/, index.html, package*.json, public/, src/, tsconfig*.json, vite.config.ts, node_modules/
- Commit des changements avec le message "refactor: restructure project as monorepo"
- Préparation de la structure monorepo pour développement full-stack

---

### 16. Création d'un projet Laravel dans le dossier back
**Prompt :** "now, i want you to create inside "back" folder a new laravel project (latest version), give me the hand of CLI choices if necessary"

**Actions réalisées :**
- Utilisation de Laravel Sail pour créer un projet Laravel avec Docker
- Commande exécutée : `curl -s https://laravel.build/laravel-backend | bash`
- Installation de Laravel 12.x (dernière version) avec :
  - Base de données MySQL
  - Redis pour le cache
  - Mailpit pour les emails de développement  
  - Meilisearch pour la recherche
  - Selenium pour les tests
- Déplacement des fichiers Laravel vers le dossier back/
- Configuration automatique avec Docker Compose et Laravel Sail
- Base de données initialisée avec les migrations par défaut

---

### 17. Création d'une API pour retourner des utilisateurs factices
**Prompt :** "create a new API (no view redirection) that returns false users"

**Actions réalisées :**
- Création du fichier routes/api.php avec la route GET /api/users
- Configuration du bootstrap/app.php pour inclure les routes API
- Création du UserController dans app/Http/Controllers/API/
- Création du FakeUserSeeder pour générer 20 utilisateurs factices
- Exécution des migrations et du seeder :
  - `./vendor/bin/sail artisan migrate`
  - `./vendor/bin/sail artisan db:seed --class=FakeUserSeeder`
- Démarrage de Laravel Sail : `./vendor/bin/sail up -d`
- Test réussi de l'API : `curl http://localhost/api/users`
- Retour JSON avec succès : 20 utilisateurs factices avec id, name, email, created_at

---

### 18. Création de tests pour l'API Users avec approche TDD
**Prompt :** "I want to start working on the backend with a TDD approach. To start this, can you create a test for the users API ? (only the testing the existing code)"

**Actions réalisées :**
- Création du fichier tests/Feature/UsersApiTest.php avec 6 tests complets :
  1. **test_users_api_returns_successful_response** - Vérifie le statut 200
  2. **test_users_api_returns_correct_json_structure** - Valide la structure JSON
  3. **test_users_api_returns_correct_data_format** - Contrôle le format des données
  4. **test_users_api_returns_users_in_correct_order** - Test de l'ordre (newest first)
  5. **test_users_api_works_with_empty_database** - Gestion de la base vide
  6. **test_users_api_does_not_expose_sensitive_data** - Sécurité (pas de password/token)
- Utilisation de RefreshDatabase et WithFaker traits
- Tests avec User::factory pour créer des données de test
- Exécution des tests : **8 passed (50 assertions)** ✅
- Base solide établie pour l'approche TDD du backend

---

## Notes
- Ce fichier sera mis à jour à chaque nouveau prompt utilisateur
- Format : Prompt → Actions réalisées
- Objectif : Traçabilité complète du processus de développement