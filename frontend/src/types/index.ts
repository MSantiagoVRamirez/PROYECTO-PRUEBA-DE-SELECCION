export interface Usuario {
  id: number
  nombre: string
  email: string
  tipo_identificacion: string
  numero_identificacion: string
  telefono?: string | null
  direccion?: string | null
  fecha_nacimiento?: string | null // YYYY-MM-DD
  created_at?: string
  updated_at?: string
}

export interface Autor {
  id: number
  nombre: string
  nacionalidad?: string | null
  fecha_nacimiento?: string | null
  fecha_fallecimiento?: string | null
  biografia?: string | null
  created_at?: string
  updated_at?: string
}

export interface Genero {
  id: number
  nombre: string
  descripcion?: string | null
  created_at?: string
  updated_at?: string
}

export interface Libro {
  id: number
  titulo: string
  resumen?: string | null
  anio_publicacion?: number | null
  isbn?: string | null
  stock: number
  created_at?: string
  updated_at?: string
}

export interface Prestamo {
  id: number
  usuario_id: number
  libro_id: number
  fecha_prestamo: string // YYYY-MM-DD
  fecha_vencimiento: string // YYYY-MM-DD
  fecha_devolucion?: string | null
  estado: 'activo' | 'devuelto' | 'vencido'
  observaciones?: string | null
  created_at?: string
  updated_at?: string
}

export interface Paginated<T> {
  data: T[]
  total: number
  per_page: number
  current_page: number
  last_page: number
}
