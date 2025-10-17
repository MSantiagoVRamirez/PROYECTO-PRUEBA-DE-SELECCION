import { ReactNode } from 'react'
import { Header } from '../shared/Header'
import { Sidebar } from '../shared/Sidebar'
import './layout.css'

type Props = { children: ReactNode }

export function AppLayout({ children }: Props) {
  return (
    <div className="app-shell">
      <Header />
      <div className="app-body">
        <Sidebar />
        <main className="app-content">{children}</main>
      </div>
    </div>
  )
}

