export interface KanbanTask {
  id: number
  title: string
  description?: string
  status: 'todo' | 'in-progress' | 'done'
  created_at: string
  updated_at: string
}

export interface ApiResponse {
  success: boolean
  message: string
  data: KanbanTask[]
  count: number
}

export type KanbanColumn = 'todo' | 'in-progress' | 'done'