import { useEffect, useState } from 'react'
import { api, getErrorMessage } from '../api/client'
import {
  ResponsiveContainer,
  LineChart, Line, XAxis, YAxis, Tooltip, CartesianGrid,
  BarChart, Bar, Legend,
  PieChart, Pie, Cell
} from 'recharts'

type Overview = {
  total_libros: number
  total_usuarios: number
  prestamos_activos: number
  prestamos_vencidos: number
  top_libros: { libro_id: number; titulo: string; total: number }[]
}

type Serie = { fecha: string; total: number }
type TopItem = { titulo: string; total: number }
type GroupItem = { nombre: string; total: number }
type Disponibilidad = { id: number; titulo: string; stock: number; activos: number; disponibles: number }
type TasaTiempo = { total: number; devueltosATiempo: number; devueltosVencidos: number; activosVencidos: number; activosEnTiempo: number }

const PIE_COLORS = ['#60a5fa', '#93c5fd', '#bfdbfe', '#c084fc', '#fbbf24', '#34d399']

function Card({ title, children }: { title: string; children: React.ReactNode }) {
  return (
    <section style={{ background: '#fff', border: '1px solid #e5e7eb', borderRadius: 12, padding: 16 }}>
      <h2 style={{ margin: '0 0 8px', fontSize: 18, color: '#0f172a' }}>{title}</h2>
      {children}
    </section>
  )
}

export function Estadisticas() {
  const [dias, setDias] = useState(30)
  const [loading, setLoading] = useState(false)
  const [error, setError] = useState<string | null>(null)

  const [overview, setOverview] = useState<Overview | null>(null)
  const [serie, setSerie] = useState<Serie[]>([])
  const [porGenero, setPorGenero] = useState<GroupItem[]>([])
  const [porAutor, setPorAutor] = useState<GroupItem[]>([])
  const [tasa, setTasa] = useState<TasaTiempo | null>(null)
  const [disp, setDisp] = useState<Disponibilidad[]>([])

  async function fetchAll() {
    try {
      setLoading(true)
      const [ov, se, pg, pa, tt, dl] = await Promise.all([
        api.get<Overview>('/estadisticas/overview', { params: { dias } }),
        api.get<Serie[]>('/estadisticas/serie-prestamos', { params: { dias } }),
        api.get<GroupItem[]>('/estadisticas/prestamos-por-genero', { params: { dias } }),
        api.get<GroupItem[]>('/estadisticas/prestamos-por-autor', { params: { dias } }),
        api.get<TasaTiempo>('/estadisticas/tasa-tiempo', { params: { dias } }),
        api.get<Disponibilidad[]>('/estadisticas/disponibilidad-libros'),
      ])
      setOverview(ov.data)
      setSerie(se.data)
      setPorGenero(pg.data)
      setPorAutor(pa.data)
      setTasa(tt.data)
      setDisp(dl.data)
      setError(null)
    } catch (err) {
      setError(getErrorMessage(err))
    } finally {
      setLoading(false)
    }
  }

  useEffect(() => { fetchAll() }, [dias])

  return (
    <div>
      <div className="toolbar" style={{ marginBottom: 16 }}>
        <div style={{ fontWeight: 700 }}>Estadísticas</div>
        <div style={{ marginLeft: 'auto' }}>
          <select className="select" value={dias} onChange={e => setDias(Number(e.target.value))}>
            <option value={7}>Últimos 7 días</option>
            <option value={30}>Últimos 30 días</option>
            <option value={90}>Últimos 90 días</option>
          </select>
        </div>
      </div>

      {error && <div style={{ color: '#b91c1c', marginBottom: 12 }}>{error}</div>}

      {/* KPIs simples */}
      {overview && (
        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(4, minmax(0, 1fr))', gap: 12, marginBottom: 16 }}>
          {[{ label: 'Libros', value: overview.total_libros }, { label: 'Usuarios', value: overview.total_usuarios }, { label: 'Préstamos activos', value: overview.prestamos_activos }, { label: 'Vencidos', value: overview.prestamos_vencidos }].map((k) => (
            <section key={k.label} style={{ background: '#fff', border: '1px solid #e5e7eb', borderRadius: 12, padding: 16 }}>
              <div style={{ fontSize: 12, color: '#334155' }}>{k.label}</div>
              <div style={{ fontWeight: 700, fontSize: 24 }}>{k.value}</div>
            </section>
          ))}
        </div>
      )}

      <div style={{ display: 'grid', gridTemplateColumns: '1.2fr 0.8fr', gap: 16, marginBottom: 16 }}>
        <Card title="Préstamos diarios">
          <div style={{ height: 260 }}>
            <ResponsiveContainer>
              <LineChart data={serie} margin={{ left: 10, right: 10, top: 10 }}>
                <CartesianGrid strokeDasharray="3 3" />
                <XAxis dataKey="fecha" tick={{ fontSize: 12 }} />
                <YAxis allowDecimals={false} width={30} />
                <Tooltip />
                <Line type="monotone" dataKey="total" stroke="#60a5fa" strokeWidth={2} dot={false} />
              </LineChart>
            </ResponsiveContainer>
          </div>
        </Card>

        <Card title="Top libros (préstamos)">
          <div style={{ height: 260 }}>
            <ResponsiveContainer>
              <BarChart data={overview?.top_libros || []} margin={{ left: 10, right: 10, top: 10 }}>
                <CartesianGrid strokeDasharray="3 3" />
                <XAxis dataKey="titulo" tick={{ fontSize: 12 }} interval={0} angle={-20} height={60} />
                <YAxis allowDecimals={false} width={30} />
                <Tooltip />
                <Bar dataKey="total" fill="#93c5fd" />
              </BarChart>
            </ResponsiveContainer>
          </div>
        </Card>
      </div>

      <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 16, marginBottom: 16 }}>
        <Card title="Préstamos por género">
          <div style={{ height: 280 }}>
            <ResponsiveContainer>
              <PieChart>
                <Pie dataKey="total" nameKey="nombre" data={porGenero} outerRadius={100} label>
                  {porGenero.map((_, i) => <Cell key={i} fill={PIE_COLORS[i % PIE_COLORS.length]} />)}
                </Pie>
                <Tooltip />
                <Legend />
              </PieChart>
            </ResponsiveContainer>
          </div>
        </Card>

        <Card title="Préstamos por autor">
          <div style={{ height: 280 }}>
            <ResponsiveContainer>
              <PieChart>
                <Pie dataKey="total" nameKey="nombre" data={porAutor} outerRadius={100} label>
                  {porAutor.map((_, i) => <Cell key={i} fill={PIE_COLORS[(i+2) % PIE_COLORS.length]} />)}
                </Pie>
                <Tooltip />
                <Legend />
              </PieChart>
            </ResponsiveContainer>
          </div>
        </Card>
      </div>

      <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 16 }}>
        <Card title="Tasa: a tiempo vs. vencidos">
          <div style={{ height: 280 }}>
            <ResponsiveContainer>
              <BarChart data={[
                { name: 'Devueltos a tiempo', total: tasa?.devueltosATiempo || 0 },
                { name: 'Devueltos vencidos', total: tasa?.devueltosVencidos || 0 },
                { name: 'Activos en tiempo', total: tasa?.activosEnTiempo || 0 },
                { name: 'Activos vencidos', total: tasa?.activosVencidos || 0 },
              ]} margin={{ left: 10, right: 10, top: 10 }}>
                <CartesianGrid strokeDasharray="3 3" />
                <XAxis dataKey="name" tick={{ fontSize: 12 }} interval={0} angle={-15} height={50} />
                <YAxis allowDecimals={false} width={30} />
                <Tooltip />
                <Bar dataKey="total" fill="#60a5fa" />
              </BarChart>
            </ResponsiveContainer>
          </div>
        </Card>

        <Card title="Disponibilidad por libro (menor a mayor)">
          <div style={{ height: 280 }}>
            <ResponsiveContainer>
              <BarChart data={[...disp].sort((a,b) => a.disponibles - b.disponibles).slice(0, 10)} margin={{ left: 10, right: 10, top: 10 }}>
                <CartesianGrid strokeDasharray="3 3" />
                <XAxis dataKey="titulo" tick={{ fontSize: 12 }} interval={0} angle={-20} height={70} />
                <YAxis allowDecimals={false} width={30} />
                <Tooltip />
                <Legend />
                <Bar dataKey="disponibles" name="Disponibles" fill="#93c5fd" />
                <Bar dataKey="activos" name="Activos" fill="#c084fc" />
              </BarChart>
            </ResponsiveContainer>
          </div>
        </Card>
      </div>

      {loading && <div style={{ marginTop: 12 }}>Cargando…</div>}
    </div>
  )
}
