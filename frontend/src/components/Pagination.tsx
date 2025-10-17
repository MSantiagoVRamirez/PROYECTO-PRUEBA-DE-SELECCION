type Props = {
  page: number
  lastPage: number
  onChange: (page: number) => void
}

export function Pagination({ page, lastPage, onChange }: Props) {
  if (lastPage <= 1) return null

  const prev = () => onChange(Math.max(1, page - 1))
  const next = () => onChange(Math.min(lastPage, page + 1))

  const pages: number[] = []
  // simple windowed pagination
  const start = Math.max(1, page - 2)
  const end = Math.min(lastPage, page + 2)
  for (let p = start; p <= end; p++) pages.push(p)

  return (
    <div style={{ display: 'flex', alignItems: 'center', gap: 6, marginTop: 12 }}>
      <button className="btn sm secondary" disabled={page === 1} onClick={prev}>Anterior</button>
      {start > 1 && <span>…</span>}
      {pages.map(p => (
        <button key={p} className={`btn sm ${p === page ? '' : 'secondary'}`} onClick={() => onChange(p)}>{p}</button>
      ))}
      {end < lastPage && <span>…</span>}
      <button className="btn sm secondary" disabled={page === lastPage} onClick={next}>Siguiente</button>
    </div>
  )
}

