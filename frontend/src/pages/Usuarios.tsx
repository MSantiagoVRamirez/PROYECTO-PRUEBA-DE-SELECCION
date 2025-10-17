import { useEffect, useMemo, useState } from 'react'
import { api, getErrorMessage } from '../api/client'
import type { Usuario, Paginated } from '../types'
import { Modal } from '../components/Modal'
import { Button, Field, Input } from '../components/Form'
import { EditIcon, TrashIcon } from '../components/Icons'
import { Pagination } from '../components/Pagination'

type UsuarioForm = Partial<Usuario>

export function Usuarios() {
  const [data, setData] = useState<Usuario[]>([])
  const [loading, setLoading] = useState(false)
  const [error, setError] = useState<string | null>(null)
  const [query, setQuery] = useState('')
  const [page, setPage] = useState(1)
  const [lastPage, setLastPage] = useState(1)

  const [open, setOpen] = useState(false)
  const [editing, setEditing] = useState<Usuario | null>(null)
  const [form, setForm] = useState<UsuarioForm>({ nombre: '', email: '', tipo_identificacion: 'CC', numero_identificacion: '' })
  const [formErrors, setFormErrors] = useState<Record<string, string>>({})

  const filtered = useMemo(() => {
    const q = query.trim().toLowerCase()
    if (!q) return data
    return data.filter(u =>
      u.nombre.toLowerCase().includes(q) || u.email.toLowerCase().includes(q) || u.numero_identificacion.toLowerCase().includes(q)
    )
  }, [data, query])

  async function fetchAll(p = page) {
    try {
      setLoading(true)
      const res = await api.get<Paginated<Usuario>>('/usuarios', { params: { page: p, per_page: 10 } })
      setData(res.data.data)
      setLastPage(res.data.last_page)
      setError(null)
    } catch (err) {
      setError(getErrorMessage(err))
    } finally {
      setLoading(false)
    }
  }

  useEffect(() => { fetchAll(page) }, [page])

  function openCreate() {
    setEditing(null)
    setForm({ nombre: '', email: '', tipo_identificacion: 'CC', numero_identificacion: '', telefono: '', direccion: '', fecha_nacimiento: '' })
    setFormErrors({})
    setOpen(true)
  }
  function openEdit(item: Usuario) {
    setEditing(item)
    setForm({ ...item })
    setFormErrors({})
    setOpen(true)
  }
  function closeModal() { setOpen(false) }

  function validate(values: UsuarioForm): Record<string, string> {
    const errs: Record<string, string> = {}
    if (!values.nombre || values.nombre.trim() === '') errs.nombre = 'Nombre es requerido'
    if (!values.email || values.email.trim() === '') errs.email = 'Email es requerido'
    else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(values.email)) errs.email = 'Formato de email inválido'
    if (!values.tipo_identificacion || values.tipo_identificacion.trim() === '') errs.tipo_identificacion = 'Tipo de identificación es requerido'
    if (!values.numero_identificacion || values.numero_identificacion.trim() === '') errs.numero_identificacion = 'Número de identificación es requerido'
    return errs
  }

  async function onSubmit(e: React.FormEvent) {
    e.preventDefault()
    try {
      const errs = validate(form)
      setFormErrors(errs)
      if (Object.keys(errs).length) return
      const payload: Partial<Usuario> = {
        nombre: form.nombre,
        email: form.email,
        tipo_identificacion: form.tipo_identificacion,
        numero_identificacion: form.numero_identificacion,
        telefono: form.telefono || '',
        direccion: form.direccion || '',
        fecha_nacimiento: form.fecha_nacimiento || null,
      }
      if (editing) {
        await api.put(`/usuarios/${editing.id}`, payload)
      } else {
        await api.post('/usuarios', payload)
      }
      closeModal()
      fetchAll()
    } catch (err) {
      alert(getErrorMessage(err))
    }
  }

  async function onDelete(item: Usuario) {
    if (!confirm(`¿Eliminar usuario "${item.nombre}"?`)) return
    try {
      await api.delete(`/usuarios/${item.id}`)
      fetchAll()
    } catch (err) {
      alert(getErrorMessage(err))
    }
  }

  return (
    <div>
      <div className="toolbar">
        <Input className="input search" placeholder="Buscar por nombre, email o identificación" value={query} onChange={e => { setQuery(e.target.value); setPage(1) }} />
        <Button onClick={openCreate}>Añadir usuario</Button>
      </div>

      {error && <div style={{ color: '#b91c1c', marginBottom: 8 }}>{error}</div>}

      <div style={{ overflowX: 'auto' }}>
        <table className="table">
          <thead>
            <tr>
              <th>Nombre</th>
              <th>Email</th>
              <th>Tipo ID</th>
              <th>No. ID</th>
              <th>Teléfono</th>
              <th style={{ width: 120 }}>Acciones</th>
            </tr>
          </thead>
          <tbody>
            {loading ? (
              <tr><td colSpan={6}>Cargando...</td></tr>
            ) : filtered.length ? filtered.map(item => (
              <tr key={item.id}>
                <td>{item.nombre}</td>
                <td>{item.email}</td>
                <td>{item.tipo_identificacion}</td>
                <td>{item.numero_identificacion}</td>
                <td>{item.telefono || '-'}</td>
                <td>
                  <div style={{ display: 'flex', gap: 6, flexWrap: 'wrap' }}>
                    <Button className="btn sm secondary icon" aria-label="Editar" title="Editar" onClick={() => openEdit(item)}>
                      <EditIcon />
                    </Button>
                    <Button className="btn sm danger icon" aria-label="Eliminar" title="Eliminar" onClick={() => onDelete(item)}>
                      <TrashIcon />
                    </Button>
                  </div>
                </td>
              </tr>
            )) : (
              <tr><td colSpan={6}>Sin resultados</td></tr>
            )}
          </tbody>
        </table>
      </div>

      <Pagination page={page} lastPage={lastPage} onChange={setPage} />

      <Modal open={open} onClose={closeModal} title={editing ? 'Editar usuario' : 'Nuevo usuario'}>
        <form onSubmit={onSubmit}>
          <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 12 }}>
            <Field label="Nombre">
              <Input required className={`input ${formErrors.nombre ? 'error' : ''}`} value={form.nombre || ''} onChange={e => setForm(f => ({ ...f, nombre: e.target.value }))} />
              {formErrors.nombre && <div className="error-text">{formErrors.nombre}</div>}
            </Field>
            <Field label="Email">
              <Input required type="email" className={`input ${formErrors.email ? 'error' : ''}`} value={form.email || ''} onChange={e => setForm(f => ({ ...f, email: e.target.value }))} />
              {formErrors.email && <div className="error-text">{formErrors.email}</div>}
            </Field>
          </div>
          <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 12 }}>
            <Field label="Tipo identificación">
              <Input className={`input ${formErrors.tipo_identificacion ? 'error' : ''}`} value={form.tipo_identificacion || ''} onChange={e => setForm(f => ({ ...f, tipo_identificacion: e.target.value }))} />
              {formErrors.tipo_identificacion && <div className="error-text">{formErrors.tipo_identificacion}</div>}
            </Field>
            <Field label="Número identificación">
              <Input className={`input ${formErrors.numero_identificacion ? 'error' : ''}`} value={form.numero_identificacion || ''} onChange={e => setForm(f => ({ ...f, numero_identificacion: e.target.value }))} />
              {formErrors.numero_identificacion && <div className="error-text">{formErrors.numero_identificacion}</div>}
            </Field>
          </div>
          <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 12 }}>
            <Field label="Teléfono"><Input value={form.telefono || ''} onChange={e => setForm(f => ({ ...f, telefono: e.target.value }))} /></Field>
            <Field label="Dirección"><Input value={form.direccion || ''} onChange={e => setForm(f => ({ ...f, direccion: e.target.value }))} /></Field>
          </div>
          <Field label="Fecha nacimiento"><Input type="date" value={form.fecha_nacimiento || ''} onChange={e => setForm(f => ({ ...f, fecha_nacimiento: e.target.value }))} /></Field>
          <div style={{ display: 'flex', justifyContent: 'flex-end', gap: 8, marginTop: 8 }}>
            <Button type="button" className="btn secondary" onClick={closeModal}>Cancelar</Button>
            <Button type="submit">Guardar</Button>
          </div>
        </form>
      </Modal>
    </div>
  )
}
