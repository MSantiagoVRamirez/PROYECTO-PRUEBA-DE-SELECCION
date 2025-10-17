import { NavLink } from 'react-router-dom'

export function Sidebar() {
  return (
    <aside className="app-sidebar">
      <nav>
        <ul>
          <li><NavLink to="/libros" className={({isActive}) => isActive ? 'active' : ''}>Libros</NavLink></li>
          <li><NavLink to="/usuarios" className={({isActive}) => isActive ? 'active' : ''}>Usuarios</NavLink></li>
          <li><NavLink to="/prestamos" className={({isActive}) => isActive ? 'active' : ''}>Préstamos</NavLink></li>
          <li><NavLink to="/autores" className={({isActive}) => isActive ? 'active' : ''}>Autores</NavLink></li>
          <li><NavLink to="/generos" className={({isActive}) => isActive ? 'active' : ''}>Géneros</NavLink></li>
          <li><NavLink to="/estadisticas" className={({isActive}) => isActive ? 'active' : ''}>Estadísticas</NavLink></li>
        </ul>
      </nav>
    </aside>
  )
}

