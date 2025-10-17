import { useEffect, useMemo, useState } from 'react'
import { api, getErrorMessage } from '../api/client'
import type { Autor, Paginated } from '../types'
import { Modal } from '../components/Modal'
import { Button, Field, Input, Textarea } from '../components/Form'
import { EditIcon, TrashIcon } from '../components/Icons'
import { Pagination } from '../components/Pagination'

type AutorForm = Partial<Autor>

export function Autores() {
  const [data, setData] = useState<Autor[]>([])
  const [loading, setLoading] = useState(false)
  const [error, setError] = useState<string | null>(null)
  const [query, setQuery] = useState('')
  const [page, setPage] = useState(1)
  const [lastPage, setLastPage] = useState(1)

  const [open, setOpen] = useState(false)
  const [editing, setEditing] = useState<Autor | null>(null)
  const [form, setForm] = useState<AutorForm>({ nombre: '' })
  const [formErrors, setFormErrors] = useState<Record<string, string>>({})

  const filtered = useMemo(() => {
    const q = query.trim().toLowerCase()
    if (!q) return data
    return data.filter(a =>
      a.nombre.toLowerCase().includes(q) || (a.nacionalidad || '').toLowerCase().includes(q)
    )
  }, [data, query])

  async function fetchAll(p = page) {
    try {
      setLoading(true)
      const res = await api.get<Paginated<Autor>>('/autores', { params: { page: p, per_page: 10 } })
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
    setForm({ nombre: '', nacionalidad: '', fecha_nacimiento: '', fecha_fallecimiento: '', biografia: '' })
    setFormErrors({})
    setOpen(true)
  }
  function openEdit(item: Autor) {
    setEditing(item)
    setForm({ ...item })
    setFormErrors({})
    setOpen(true)
  }
  function closeModal() { setOpen(false) }

  function validate(values: AutorForm): Record<string, string> {
    const errs: Record<string, string> = {}
    if (!values.nombre || values.nombre.trim() === '') errs.nombre = 'Nombre es requerido'
    if (values.fecha_nacimiento && values.fecha_fallecimiento) {
      if (values.fecha_nacimiento > values.fecha_fallecimiento) errs.fecha_fallecimiento = 'Fallecimiento debe ser posterior al nacimiento'
    }
    return errs
  }

  async function onSubmit(e: React.FormEvent) {
    e.preventDefault()
    try {
      const errs = validate(form)
      setFormErrors(errs)
      if (Object.keys(errs).length) return
      const payload: Partial<Autor> = {
        nombre: form.nombre,
        nacionalidad: form.nacionalidad || '',
        fecha_nacimiento: form.fecha_nacimiento || null,
        fecha_fallecimiento: form.fecha_fallecimiento || null,
        biografia: form.biografia || '',
      }
      if (editing) {
        await api.put(`/autores/${editing.id}`, payload)
      } else {
        await api.post('/autores', payload)
      }
      closeModal()
      fetchAll()
    } catch (err) {
      alert(getErrorMessage(err))
    }
  }

  async function onDelete(item: Autor) {
    if (!confirm(`¿Eliminar autor "${item.nombre}"?`)) return
    try {
      await api.delete(`/autores/${item.id}`)
      fetchAll()
    } catch (err) {
      alert(getErrorMessage(err))
    }
  }

  return (
    <div>
      <div className="toolbar">
        <Input className="input search" placeholder="Buscar por nombre o nacionalidad" value={query} onChange={e => { setQuery(e.target.value); setPage(1) }} />
        <Button onClick={openCreate}>Añadir autor</Button>
      </div>

      {error && <div style={{ color: '#b91c1c', marginBottom: 8 }}>{error}</div>}

      <div style={{ overflowX: 'auto' }}>
        <table className="table">
          <thead>
            <tr>
              <th>Nombre</th>
              <th>Nacionalidad</th>
              <th>Nacimiento</th>
              <th>Fallecimiento</th>
              <th style={{ width: 120 }}>Acciones</th>
            </tr>
          </thead>
          <tbody>
            {loading ? (
              <tr><td colSpan={5}>Cargando...</td></tr>
            ) : filtered.length ? filtered.map(item => (
              <tr key={item.id}>
                <td>{item.nombre}</td>
                <td>{item.nacionalidad || '-'}</td>
                <td>{item.fecha_nacimiento || '-'}</td>
                <td>{item.fecha_fallecimiento || '-'}</td>
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
              <tr><td colSpan={5}>Sin resultados</td></tr>
            )}
          </tbody>
        </table>
      </div>

      <Pagination page={page} lastPage={lastPage} onChange={setPage} />

      <Modal open={open} onClose={closeModal} title={editing ? 'Editar autor' : 'Nuevo autor'}>
        <form onSubmit={onSubmit}>
          <Field label="Nombre">
            <Input required className={`input ${formErrors.nombre ? 'error' : ''}`} value={form.nombre || ''} onChange={e => setForm(f => ({ ...f, nombre: e.target.value }))} />
            {formErrors.nombre && <div className="error-text">{formErrors.nombre}</div>}
          </Field>
          <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 12 }}>
            <Field label="Nacionalidad"><Input value={form.nacionalidad || ''} onChange={e => setForm(f => ({ ...f, nacionalidad: e.target.value }))} /></Field>
            <Field label="Fecha nacimiento"><Input type="date" className="input" value={form.fecha_nacimiento || ''} onChange={e => setForm(f => ({ ...f, fecha_nacimiento: e.target.value }))} /></Field>
          </div>
          <div style={{ display: 'grid', gridTemplateColumns: '1fr', gap: 12 }}>
            <Field label="Fecha fallecimiento">
              <Input type="date" className={`input ${formErrors.fecha_fallecimiento ? 'error' : ''}`} value={form.fecha_fallecimiento || ''} onChange={e => setForm(f => ({ ...f, fecha_fallecimiento: e.target.value }))} />
              {formErrors.fecha_fallecimiento && <div className="error-text">{formErrors.fecha_fallecimiento}</div>}
            </Field>
          </div>
          <Field label="Biografía"><Textarea rows={3} value={form.biografia || ''} onChange={e => setForm(f => ({ ...f, biografia: e.target.value }))} /></Field>
          <div style={{ display: 'flex', justifyContent: 'flex-end', gap: 8, marginTop: 8 }}>
            <Button type="button" className="btn secondary" onClick={closeModal}>Cancelar</Button>
            <Button type="submit">Guardar</Button>
          </div>
        </form>
      </Modal>
    </div>
  )
}
