import axios from 'axios'

const baseURL = `${import.meta.env.VITE_API_URL?.replace(/\/$/, '') || ''}/api`

export const api = axios.create({
  baseURL,
  headers: {
    'Content-Type': 'application/json',
  },
  timeout: 15000,
})

// Simple helper for errors
export function getErrorMessage(err: unknown): string {
  if (axios.isAxiosError(err)) {
    return (
      err.response?.data?.message || err.message || 'Error de red'
    )
  }
  return 'Error desconocido'
}

