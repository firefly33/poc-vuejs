# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a full-stack task management application with a Laravel API backend and Vue.js TypeScript frontend. The project demonstrates task management functionality with both a simple todo list and a Kanban-style board interface.

## Architecture

### Backend (Laravel)
- Located in `/back/` directory
- Laravel 12 application with Sanctum for API authentication
- Task management API with filtering capabilities
- Uses Service layer pattern (TaskService) for business logic
- RESTful API endpoints for tasks at `/api/tasks`

### Frontend (Vue.js)
- Located in `/front/` directory
- Vue 3 with TypeScript, Vite, and Pinia for state management
- Two main interfaces:
  - Simple todo list (`/` route)
  - Kanban board (`/gestion-des-taches` route)
- Uses Vue Router for navigation

## Development Commands

### Backend (Laravel)
```bash
cd back/

# Development server with all services
composer run dev

# Individual commands
php artisan serve                    # Start Laravel server
php artisan test                     # Run PHPUnit tests
php artisan migrate                  # Run database migrations
php artisan db:seed                  # Seed database with test data

# Build assets
npm run dev                          # Development build
npm run build                        # Production build
```

### Frontend (Vue.js)
```bash
cd front/

# Development
npm run dev                          # Start Vite dev server

# Production
npm run build                        # Build for production with type checking
npm run preview                      # Preview production build
```

## Key Components

### Backend Models & Controllers
- `Task` model with status constants (`todo`, `in-progress`, `done`)
- `TaskController` with filtering by status query parameter
- `TaskService` for business logic separation

### Frontend Components
- `TaskManagement.vue` - Kanban board interface
- `TodoList.vue` - Simple todo list interface
- `KanbanBoard.vue` - Drag and drop task board
- `KanbanCard.vue` - Individual task cards

### API Integration
- Tasks are fetched from Laravel API at `/api/tasks`
- Frontend uses Pinia store (`stores/kanban.ts`) for state management
- API responses follow consistent format with `success`, `message`, `data`, and `count` fields

## Database

Tasks have the following structure:
- `id` (primary key)
- `title` (string)
- `description` (text, optional)
- `status` (enum: 'todo', 'in-progress', 'done')
- `created_at`, `updated_at` timestamps

## CI/CD

The project includes a GitHub Actions CI pipeline (`.github/workflows/ci.yml`) that:
- Tests both frontend and backend on Node.js 18.x/20.x and PHP 8.2/8.3
- Runs type checking and builds for the Vue.js frontend
- Runs PHPUnit tests for the Laravel backend with SQLite in-memory database
- Triggers on pushes and pull requests to `main` and `develop` branches

## Development Notes

- The backend uses Laravel Sanctum for CORS handling
- Frontend and backend run on separate ports during development
- Both applications use modern tooling (Vite for frontend, Laravel Artisan for backend)
- TypeScript interfaces are defined for type safety (`KanbanTask`, `ApiResponse`)
- Tests use SQLite in-memory database for speed and isolation