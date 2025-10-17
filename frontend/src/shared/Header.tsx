import { NavLink } from 'react-router-dom'

export function Header() {
  return (
    <header className="app-header">
      <div className="brand">📚 Biblioteca</div>
      <nav className="top-nav">
        <NavLink to="/libros" className={({isActive}) => isActive ? 'active' : ''}>Libros</NavLink>
        <NavLink to="/usuarios" className={({isActive}) => isActive ? 'active' : ''}>Usuarios</NavLink>
        <NavLink to="/prestamos" className={({isActive}) => isActive ? 'active' : ''}>Préstamos</NavLink>
        <NavLink to="/autores" className={({isActive}) => isActive ? 'active' : ''}>Autores</NavLink>
        <NavLink to="/generos" className={({isActive}) => isActive ? 'active' : ''}>Géneros</NavLink>
        <NavLink to="/estadisticas" className={({isActive}) => isActive ? 'active' : ''}>Estadísticas</NavLink>
      </nav>
    </header>
  )
}

