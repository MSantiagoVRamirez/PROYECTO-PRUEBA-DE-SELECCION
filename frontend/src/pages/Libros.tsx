import { useEffect, useMemo, useState } from 'react'
import { api, getErrorMessage } from '../api/client'
import type { Libro, Paginated } from '../types'
import { Modal } from '../components/Modal'
import { Button, Field, Input, Textarea } from '../components/Form'
import { EditIcon, TrashIcon } from '../components/Icons'
import { Pagination } from '../components/Pagination'

type LibroForm = Partial<Pick<Libro, 'titulo' | 'resumen' | 'anio_publicacion' | 'isbn' | 'stock'>>

export function Libros() {
  const [data, setData] = useState<Libro[]>([])
  const [loading, setLoading] = useState(false)
  const [error, setError] = useState<string | null>(null)
  const [query, setQuery] = useState('')
  const [page, setPage] = useState(1)
  const [lastPage, setLastPage] = useState(1)

  const [open, setOpen] = useState(false)
  const [editing, setEditing] = useState<Libro | null>(null)
  const [form, setForm] = useState<LibroForm>({ titulo: '', stock: 0 })
  const [formErrors, setFormErrors] = useState<Record<string, string>>({})

  const filtered = useMemo(() => {
    const q = query.trim().toLowerCase()
    if (!q) return data
    return data.filter(l =>
      l.titulo.toLowerCase().includes(q) ||
      (l.isbn || '').toLowerCase().includes(q)
    )
  }, [data, query])

  async function fetchAll(p = page) {
    try {
      setLoading(true)
      const res = await api.get<Paginated<Libro>>('/libros', { params: { page: p, per_page: 10 } })
      setData(res.data.data)
      setLastPage(res.data.last_page)
      setError(null)
    } catch (err) {
      setError(getErrorMessage(err))
    } finally {
      setLoading(false)
    }
  }

  useEffect(() => {
    fetchAll(page)
  }, [page])

  function openCreate() {
    setEditing(null)
    setForm({ titulo: '', resumen: '', anio_publicacion: undefined, isbn: '', stock: 0 })
    setFormErrors({})
    setOpen(true)
  }
  function openEdit(item: Libro) {
    setEditing(item)
    setForm({
      titulo: item.titulo,
      resumen: item.resumen || '',
      anio_publicacion: item.anio_publicacion || undefined,
      isbn: item.isbn || '',
      stock: item.stock,
    })
    setFormErrors({})
    setOpen(true)
  }
  function closeModal() {
    setOpen(false)
  }

  function validate(values: LibroForm): Record<string, string> {
    const errs: Record<string, string> = {}
    if (!values.titulo || values.titulo.trim() === '') errs.titulo = 'Título es requerido'
    if (values.stock == null || Number.isNaN(values.stock)) errs.stock = 'Stock es requerido'
    else if (values.stock < 0) errs.stock = 'Stock debe ser >= 0'
    if (values.anio_publicacion != null) {
      const y = values.anio_publicacion
      const max = new Date().getFullYear()
      if (y < 1450 || y > max) errs.anio_publicacion = `Año debe estar entre 1450 y ${max}`
    }
    if (values.isbn && values.isbn.length > 20) errs.isbn = 'ISBN máximo 20 caracteres'
    return errs
  }

  async function onSubmit(e: React.FormEvent) {
    e.preventDefault()
    try {
      const errs = validate(form)
      setFormErrors(errs)
      if (Object.keys(errs).length) return
      if (editing) {
        await api.put(`/libros/${editing.id}`, form)
      } else {
        await api.post('/libros', form)
      }
      closeModal()
      fetchAll()
    } catch (err) {
      alert(getErrorMessage(err))
    }
  }

  async function onDelete(item: Libro) {
    if (!confirm(`¿Eliminar libro "${item.titulo}"?`)) return
    try {
      await api.delete(`/libros/${item.id}`)
      fetchAll()
    } catch (err) {
      alert(getErrorMessage(err))
    }
  }

  return (
    <div>
      <div className="toolbar">
        <Input className="input search" placeholder="Buscar por título o ISBN" value={query} onChange={e => { setQuery(e.target.value); setPage(1) }} />
        <Button onClick={openCreate}>Añadir libro</Button>
      </div>

      {error && <div style={{ color: '#b91c1c', marginBottom: 8 }}>{error}</div>}

      <div style={{ overflowX: 'auto' }}>
        <table className="table">
          <thead>
            <tr>
              <th>Título</th>
              <th>ISBN</th>
              <th>Año</th>
              <th>Stock</th>
              <th style={{ width: 120 }}>Acciones</th>
            </tr>
          </thead>
          <tbody>
            {loading ? (
              <tr><td colSpan={5}>Cargando...</td></tr>
            ) : filtered.length ? filtered.map(item => (
              <tr key={item.id}>
                <td>{item.titulo}</td>
                <td>{item.isbn || '-'}</td>
                <td>{item.anio_publicacion ?? '-'}</td>
                <td>{item.stock}</td>
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

      <Modal open={open} onClose={closeModal} title={editing ? 'Editar libro' : 'Nuevo libro'}>
        <form onSubmit={onSubmit}>
          <Field label="Título">
            <Input required className={`input ${formErrors.titulo ? 'error' : ''}`} value={form.titulo || ''} onChange={e => setForm(f => ({ ...f, titulo: e.target.value }))} />
            {formErrors.titulo && <div className="error-text">{formErrors.titulo}</div>}
          </Field>
          <Field label="Resumen">
            <Textarea rows={3} className={`textarea ${formErrors.resumen ? 'error' : ''}`} value={form.resumen || ''} onChange={e => setForm(f => ({ ...f, resumen: e.target.value }))} />
          </Field>
          <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 12 }}>
            <Field label="Año publicación">
              <Input type="number" className={`input ${formErrors.anio_publicacion ? 'error' : ''}`} min={1450} max={new Date().getFullYear()} value={form.anio_publicacion ?? ''}
                onChange={e => setForm(f => ({ ...f, anio_publicacion: e.target.value ? Number(e.target.value) : undefined }))} />
              {formErrors.anio_publicacion && <div className="error-text">{formErrors.anio_publicacion}</div>}
            </Field>
            <Field label="ISBN">
              <Input className={`input ${formErrors.isbn ? 'error' : ''}`} value={form.isbn || ''} onChange={e => setForm(f => ({ ...f, isbn: e.target.value }))} />
              {formErrors.isbn && <div className="error-text">{formErrors.isbn}</div>}
            </Field>
          </div>
          <Field label="Stock">
            <Input type="number" min={0} required className={`input ${formErrors.stock ? 'error' : ''}`} value={form.stock ?? 0}
              onChange={e => setForm(f => ({ ...f, stock: Number(e.target.value) }))} />
            {formErrors.stock && <div className="error-text">{formErrors.stock}</div>}
          </Field>
          <div style={{ display: 'flex', justifyContent: 'flex-end', gap: 8, marginTop: 8 }}>
            <Button type="button" className="btn secondary" onClick={closeModal}>Cancelar</Button>
            <Button type="submit">Guardar</Button>
          </div>
        </form>
      </Modal>
    </div>
  )
}
