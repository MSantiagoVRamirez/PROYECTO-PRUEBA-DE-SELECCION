import { Routes, Route, Navigate } from 'react-router-dom'
import { AppLayout } from './layouts/AppLayout'
import { Usuarios } from './pages/Usuarios'
import { Libros } from './pages/Libros'
import { Prestamos } from './pages/Prestamos'
import { Autores } from './pages/Autores'
import { Generos } from './pages/Generos'
import { Estadisticas } from './pages/Estadisticas'

export default function App() {
  return (
    <AppLayout>
      <Routes>
        <Route path="/" element={<Navigate to="/libros" replace />} />
        <Route path="/usuarios" element={<Usuarios />} />
        <Route path="/autores" element={<Autores />} />
        <Route path="/generos" element={<Generos />} />
        <Route path="/libros" element={<Libros />} />
        <Route path="/prestamos" element={<Prestamos />} />
        <Route path="/estadisticas" element={<Estadisticas />} />
        <Route path="*" element={<div style={{ padding: 24 }}>No encontrado</div>} />
      </Routes>
    </AppLayout>
  )
}

