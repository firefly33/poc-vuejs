export interface KanbanTask {
  id: number
  title: string
  description?: string
  status: 'todo' | 'in-progress' | 'done'
  createdAt: Date
}

export type KanbanColumn = 'todo' | 'in-progress' | 'done'