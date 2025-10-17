import { useEffect, useMemo, useState } from 'react'
import { api, getErrorMessage } from '../api/client'
import type { Genero, Paginated } from '../types'
import { Modal } from '../components/Modal'
import { Button, Field, Input, Textarea } from '../components/Form'
import { EditIcon, TrashIcon } from '../components/Icons'
import { Pagination } from '../components/Pagination'

type GeneroForm = Partial<Genero>

export function Generos() {
  const [data, setData] = useState<Genero[]>([])
  const [loading, setLoading] = useState(false)
  const [error, setError] = useState<string | null>(null)
  const [query, setQuery] = useState('')
  const [page, setPage] = useState(1)
  const [lastPage, setLastPage] = useState(1)

  const [open, setOpen] = useState(false)
  const [editing, setEditing] = useState<Genero | null>(null)
  const [form, setForm] = useState<GeneroForm>({ nombre: '' })
  const [formErrors, setFormErrors] = useState<Record<string, string>>({})

  const filtered = useMemo(() => {
    const q = query.trim().toLowerCase()
    if (!q) return data
    return data.filter(g => g.nombre.toLowerCase().includes(q))
  }, [data, query])

  async function fetchAll(p = page) {
    try {
      setLoading(true)
      const res = await api.get<Paginated<Genero>>('/generos', { params: { page: p, per_page: 10 } })
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
    setForm({ nombre: '', descripcion: '' })
    setFormErrors({})
    setOpen(true)
  }
  function openEdit(item: Genero) {
    setEditing(item)
    setForm({ ...item })
    setFormErrors({})
    setOpen(true)
  }
  function closeModal() { setOpen(false) }

  function validate(values: GeneroForm): Record<string, string> {
    const errs: Record<string, string> = {}
    if (!values.nombre || values.nombre.trim() === '') errs.nombre = 'Nombre es requerido'
    return errs
  }

  async function onSubmit(e: React.FormEvent) {
    e.preventDefault()
    try {
      const errs = validate(form)
      setFormErrors(errs)
      if (Object.keys(errs).length) return
      const payload: Partial<Genero> = { nombre: form.nombre, descripcion: form.descripcion || '' }
      if (editing) {
        await api.put(`/generos/${editing.id}`, payload)
      } else {
        await api.post('/generos', payload)
      }
      closeModal()
      fetchAll()
    } catch (err) {
      alert(getErrorMessage(err))
    }
  }

  async function onDelete(item: Genero) {
    if (!confirm(`¿Eliminar género "${item.nombre}"?`)) return
    try {
      await api.delete(`/generos/${item.id}`)
      fetchAll()
    } catch (err) {
      alert(getErrorMessage(err))
    }
  }

  return (
    <div>
      <div className="toolbar">
        <Input className="input search" placeholder="Buscar por nombre" value={query} onChange={e => { setQuery(e.target.value); setPage(1) }} />
        <Button onClick={openCreate}>Añadir género</Button>
      </div>

      {error && <div style={{ color: '#b91c1c', marginBottom: 8 }}>{error}</div>}

      <div style={{ overflowX: 'auto' }}>
        <table className="table">
          <thead>
            <tr>
              <th>Nombre</th>
              <th>Descripción</th>
              <th style={{ width: 120 }}>Acciones</th>
            </tr>
          </thead>
          <tbody>
            {loading ? (
              <tr><td colSpan={3}>Cargando...</td></tr>
            ) : filtered.length ? filtered.map(item => (
              <tr key={item.id}>
                <td>{item.nombre}</td>
                <td>{item.descripcion || '-'}</td>
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
              <tr><td colSpan={3}>Sin resultados</td></tr>
            )}
          </tbody>
        </table>
      </div>

      <Pagination page={page} lastPage={lastPage} onChange={setPage} />

      <Modal open={open} onClose={closeModal} title={editing ? 'Editar género' : 'Nuevo género'}>
        <form onSubmit={onSubmit}>
          <Field label="Nombre">
            <Input required className={`input ${formErrors.nombre ? 'error' : ''}`} value={form.nombre || ''} onChange={e => setForm(f => ({ ...f, nombre: e.target.value }))} />
            {formErrors.nombre && <div className="error-text">{formErrors.nombre}</div>}
          </Field>
          <Field label="Descripción"><Textarea rows={3} value={form.descripcion || ''} onChange={e => setForm(f => ({ ...f, descripcion: e.target.value }))} /></Field>
          <div style={{ display: 'flex', justifyContent: 'flex-end', gap: 8, marginTop: 8 }}>
            <Button type="button" className="btn secondary" onClick={closeModal}>Cancelar</Button>
            <Button type="submit">Guardar</Button>
          </div>
        </form>
      </Modal>
    </div>
  )
}
