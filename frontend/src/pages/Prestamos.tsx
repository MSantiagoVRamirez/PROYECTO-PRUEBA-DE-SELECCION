import { useEffect, useMemo, useState } from 'react'
import { api, getErrorMessage } from '../api/client'
import type { Prestamo, Usuario, Libro, Paginated } from '../types'
import { Modal } from '../components/Modal'
import { Button, Field, Input, Select, Textarea } from '../components/Form'
import { Pagination } from '../components/Pagination'

type PrestamoForm = Partial<Pick<Prestamo, 'usuario_id' | 'libro_id' | 'fecha_prestamo' | 'fecha_vencimiento' | 'observaciones'>>

function EditIcon() {
  return (
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M13.586 3.586a2 2 0 1 1 2.828 2.828l-9.9 9.9a1 1 0 0 1-.39.24l-3.18 1.06a.5.5 0 0 1-.63-.63l1.06-3.18a1 1 0 0 1 .24-.39l9.9-9.9Z"/><path d="M12.172 5 15 7.828"/></svg>
  )
}
function ReturnIcon() {
  return (
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M7 7V4L1 9l6 5V11h4a4 4 0 1 1 0 8H9v-2h2a2 2 0 1 0 0-4H7z"/></svg>
  )
}
function TrashIcon() {
  return (
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M6 2a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2h4v2H2V2h4Zm2 6h2v8H8V8Zm6 0h2v8h-2V8ZM4 4h16l-1.2 14.4A2 2 0 0 1 16.81 20H7.19a2 2 0 0 1-1.99-1.6L4 4Z"/></svg>
  )
}

export function Prestamos() {
  const [data, setData] = useState<Prestamo[]>([])
  const [usuarios, setUsuarios] = useState<Usuario[]>([])
  const [libros, setLibros] = useState<Libro[]>([])
  const [loading, setLoading] = useState(false)
  const [error, setError] = useState<string | null>(null)
  const [query, setQuery] = useState('')
  const [page, setPage] = useState(1)
  const [lastPage, setLastPage] = useState(1)

  const [open, setOpen] = useState(false)
  const [openReturn, setOpenReturn] = useState(false)
  const [selected, setSelected] = useState<Prestamo | null>(null)
  const [form, setForm] = useState<PrestamoForm>({})
  const [returnForm, setReturnForm] = useState<{ fecha_devolucion?: string; observaciones?: string }>({})
  const [formErrors, setFormErrors] = useState<Record<string, string>>({})
  const [returnErrors, setReturnErrors] = useState<Record<string, string>>({})

  const byIdUsuario = useMemo(() => new Map(usuarios.map(u => [u.id, u])), [usuarios])
  const byIdLibro = useMemo(() => new Map(libros.map(l => [l.id, l])), [libros])

  const enriched = useMemo(() => data.map(p => ({
    ...p,
    usuario_nombre: byIdUsuario.get(p.usuario_id)?.nombre || `Usuario ${p.usuario_id}`,
    libro_titulo: byIdLibro.get(p.libro_id)?.titulo || `Libro ${p.libro_id}`,
  })), [data, byIdUsuario, byIdLibro])

  const filtered = useMemo(() => {
    const q = query.trim().toLowerCase()
    if (!q) return enriched
    return enriched.filter(p =>
      p.usuario_nombre.toLowerCase().includes(q) || p.libro_titulo.toLowerCase().includes(q) || p.estado.toLowerCase().includes(q)
    )
  }, [enriched, query])

  async function fetchAll(p = page) {
    try {
      setLoading(true)
      const [pr, ur, lr] = await Promise.all([
        api.get<Paginated<Prestamo>>('/prestamos', { params: { page: p, per_page: 10 } }),
        api.get<Paginated<Usuario>>('/usuarios', { params: { per_page: 1000 } }),
        api.get<Paginated<Libro>>('/libros', { params: { per_page: 1000 } }),
      ])
      setData(pr.data.data)
      setLastPage(pr.data.last_page)
      setUsuarios(ur.data.data)
      setLibros(lr.data.data)
      setError(null)
    } catch (err) {
      setError(getErrorMessage(err))
    } finally {
      setLoading(false)
    }
  }

  useEffect(() => { fetchAll(page) }, [page])

  function openCreate() {
    setSelected(null)
    const today = new Date().toISOString().slice(0,10)
    const in7 = new Date(Date.now() + 7*24*3600*1000).toISOString().slice(0,10)
    setForm({ usuario_id: undefined, libro_id: undefined, fecha_prestamo: today, fecha_vencimiento: in7, observaciones: '' })
    setFormErrors({})
    setOpen(true)
  }

  function openReturnModal(p: Prestamo) {
    setSelected(p)
    setReturnForm({ fecha_devolucion: new Date().toISOString().slice(0,10), observaciones: '' })
    setReturnErrors({})
    setOpenReturn(true)
  }

  function openEdit(p: Prestamo) {
    // Para simplicidad, solo editaremos observaciones por ahora
    setSelected(p)
    setForm({ usuario_id: p.usuario_id, libro_id: p.libro_id, fecha_prestamo: p.fecha_prestamo, fecha_vencimiento: p.fecha_vencimiento, observaciones: p.observaciones || '' })
    setFormErrors({})
    setOpen(true)
  }

  function closeModal() { setOpen(false) }
  function closeReturn() { setOpenReturn(false) }

  function validate(values: PrestamoForm): Record<string, string> {
    const errs: Record<string, string> = {}
    if (!values.usuario_id) errs.usuario_id = 'Usuario es requerido'
    if (!values.libro_id) errs.libro_id = 'Libro es requerido'
    if (!values.fecha_prestamo) errs.fecha_prestamo = 'Fecha de préstamo es requerida'
    if (!values.fecha_vencimiento) errs.fecha_vencimiento = 'Fecha de vencimiento es requerida'
    if (values.fecha_prestamo && values.fecha_vencimiento && values.fecha_vencimiento < values.fecha_prestamo) {
      errs.fecha_vencimiento = 'Vencimiento debe ser posterior a préstamo'
    }
    return errs
  }

  function validateReturn(fd: { fecha_devolucion?: string }): Record<string, string> {
    const errs: Record<string, string> = {}
    if (!fd.fecha_devolucion) return errs
    if (selected && fd.fecha_devolucion < selected.fecha_prestamo) {
      errs.fecha_devolucion = 'Devolución no puede ser anterior a préstamo'
    }
    return errs
  }

  async function onSubmit(e: React.FormEvent) {
    e.preventDefault()
    try {
      const errs = validate(form)
      setFormErrors(errs)
      if (Object.keys(errs).length) return
      if (selected) {
        await api.put(`/prestamos/${selected.id}`, { observaciones: form.observaciones || '' })
      } else {
        await api.post('/prestamos', form)
      }
      closeModal()
      fetchAll()
    } catch (err) {
      alert(getErrorMessage(err))
    }
  }

  async function onReturnSubmit(e: React.FormEvent) {
    e.preventDefault()
    if (!selected) return
    try {
      const errs = validateReturn(returnForm)
      setReturnErrors(errs)
      if (Object.keys(errs).length) return
      await api.patch(`/prestamos/${selected.id}/devolver`, returnForm)
      closeReturn()
      fetchAll()
    } catch (err) {
      alert(getErrorMessage(err))
    }
  }

  async function onDelete(p: Prestamo) {
    if (!confirm('¿Eliminar préstamo?')) return
    try {
      await api.delete(`/prestamos/${p.id}`)
      fetchAll()
    } catch (err) {
      alert(getErrorMessage(err))
    }
  }

  return (
    <div>
      <div className="toolbar">
        <Input className="input search" placeholder="Buscar por usuario, libro o estado" value={query} onChange={e => { setQuery(e.target.value); setPage(1) }} />
        <Button onClick={openCreate}>Nuevo préstamo</Button>
      </div>

      {error && <div style={{ color: '#b91c1c', marginBottom: 8 }}>{error}</div>}

      <div style={{ overflowX: 'auto' }}>
        <table className="table">
          <thead>
            <tr>
              <th>Usuario</th>
              <th>Libro</th>
              <th>F. Préstamo</th>
              <th>F. Vencimiento</th>
              <th>F. Devolución</th>
              <th>Estado</th>
              <th style={{ width: 240 }}>Acciones</th>
            </tr>
          </thead>
          <tbody>
            {loading ? (
              <tr><td colSpan={7}>Cargando...</td></tr>
            ) : filtered.length ? filtered.map(item => (
              <tr key={item.id}>
                <td>{byIdUsuario.get(item.usuario_id)?.nombre || item.usuario_id}</td>
                <td>{byIdLibro.get(item.libro_id)?.titulo || item.libro_id}</td>
                <td>{item.fecha_prestamo}</td>
                <td>{item.fecha_vencimiento}</td>
                <td>{item.fecha_devolucion || '-'}</td>
                <td>{item.estado}</td>
                <td>
                  <div style={{ display: 'flex', gap: 6, flexWrap: 'wrap' }}>
                    <Button className="btn sm secondary icon" aria-label="Editar" title="Editar" onClick={() => openEdit(item)}>
                      <EditIcon />
                    </Button>
                    {item.estado === 'activo' && (
                      <Button className="btn sm icon" aria-label="Devolver" title="Devolver" onClick={() => openReturnModal(item)}>
                        <ReturnIcon />
                      </Button>
                    )}
                    <Button className="btn sm danger icon" aria-label="Eliminar" title="Eliminar" onClick={() => onDelete(item)}>
                      <TrashIcon />
                    </Button>
                  </div>
                </td>
              </tr>
            )) : (
              <tr><td colSpan={7}>Sin resultados</td></tr>
            )}
          </tbody>
        </table>
      </div>

      <Pagination page={page} lastPage={lastPage} onChange={setPage} />

      <Modal open={open} onClose={closeModal} title={selected ? 'Editar préstamo' : 'Nuevo préstamo'}>
        <form onSubmit={onSubmit}>
          <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 12 }}>
            <Field label="Usuario">
              <Select required className={`select ${formErrors.usuario_id ? 'error' : ''}`} value={form.usuario_id ?? ''} onChange={e => setForm(f => ({ ...f, usuario_id: e.target.value ? Number(e.target.value) : undefined }))}>
                <option value="">Seleccione...</option>
                {usuarios.map(u => <option key={u.id} value={u.id}>{u.nombre}</option>)}
              </Select>
              {formErrors.usuario_id && <div className="error-text">{formErrors.usuario_id}</div>}
            </Field>
            <Field label="Libro">
              <Select required className={`select ${formErrors.libro_id ? 'error' : ''}`} value={form.libro_id ?? ''} onChange={e => setForm(f => ({ ...f, libro_id: e.target.value ? Number(e.target.value) : undefined }))}>
                <option value="">Seleccione...</option>
                {libros.map(l => <option key={l.id} value={l.id}>{l.titulo}</option>)}
              </Select>
              {formErrors.libro_id && <div className="error-text">{formErrors.libro_id}</div>}
            </Field>
          </div>
          <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 12 }}>
            <Field label="Fecha préstamo">
              <Input type="date" required className={`input ${formErrors.fecha_prestamo ? 'error' : ''}`} value={form.fecha_prestamo || ''} onChange={e => setForm(f => ({ ...f, fecha_prestamo: e.target.value }))} />
              {formErrors.fecha_prestamo && <div className="error-text">{formErrors.fecha_prestamo}</div>}
            </Field>
            <Field label="Fecha vencimiento">
              <Input type="date" required className={`input ${formErrors.fecha_vencimiento ? 'error' : ''}`} value={form.fecha_vencimiento || ''} onChange={e => setForm(f => ({ ...f, fecha_vencimiento: e.target.value }))} />
              {formErrors.fecha_vencimiento && <div className="error-text">{formErrors.fecha_vencimiento}</div>}
            </Field>
          </div>
          <Field label="Observaciones"><Textarea rows={2} value={form.observaciones || ''} onChange={e => setForm(f => ({ ...f, observaciones: e.target.value }))} /></Field>
          <div style={{ display: 'flex', justifyContent: 'flex-end', gap: 8, marginTop: 8 }}>
            <Button type="button" className="btn secondary" onClick={closeModal}>Cancelar</Button>
            <Button type="submit">Guardar</Button>
          </div>
        </form>
      </Modal>

      <Modal open={openReturn} onClose={closeReturn} title="Devolver préstamo">
        <form onSubmit={onReturnSubmit}>
          <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 12 }}>
            <Field label="Fecha devolución">
              <Input type="date" className={`input ${returnErrors.fecha_devolucion ? 'error' : ''}`} value={returnForm.fecha_devolucion || ''} onChange={e => setReturnForm(f => ({ ...f, fecha_devolucion: e.target.value }))} />
              {returnErrors.fecha_devolucion && <div className="error-text">{returnErrors.fecha_devolucion}</div>}
            </Field>
            <Field label="Observaciones"><Input value={returnForm.observaciones || ''} onChange={e => setReturnForm(f => ({ ...f, observaciones: e.target.value }))} /></Field>
          </div>
          <div style={{ display: 'flex', justifyContent: 'flex-end', gap: 8, marginTop: 8 }}>
            <Button type="button" className="btn secondary" onClick={closeReturn}>Cancelar</Button>
            <Button type="submit">Confirmar devolución</Button>
          </div>
        </form>
      </Modal>
    </div>
  )
}
