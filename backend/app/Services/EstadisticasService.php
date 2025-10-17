<?php

namespace App\Services;

use App\Models\Libro;
use App\Models\Prestamo;
use App\Models\Usuario;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EstadisticasService
{
    public function overview(int $dias = 30, int $top = 5): array
    {
        $desde = Carbon::now()->subDays($dias)->toDateString();

        $totalLibros = Libro::count();
        $totalUsuarios = Usuario::count();
        $prestamosActivos = Prestamo::where('estado', Prestamo::ESTADO_ACTIVO)->count();
        $prestamosVencidos = Prestamo::where('estado', Prestamo::ESTADO_VENCIDO)->count();

        $topLibros = DB::table('prestamos as p')
            ->join('libros as l', 'l.id', '=', 'p.libro_id')
            ->select('p.libro_id', 'l.titulo', DB::raw('COUNT(p.id) as total'))
            ->whereDate('p.fecha_prestamo', '>=', $desde)
            ->groupBy('p.libro_id', 'l.titulo')
            ->orderByDesc('total')
            ->limit($top)
            ->get();

        return [
            'total_libros' => $totalLibros,
            'total_usuarios' => $totalUsuarios,
            'prestamos_activos' => $prestamosActivos,
            'prestamos_vencidos' => $prestamosVencidos,
            'top_libros' => $topLibros,
        ];
    }

    public function topLibros(int $dias = 30, int $limit = 5)
    {
        $desde = Carbon::now()->subDays($dias)->toDateString();
        return DB::table('prestamos as p')
            ->join('libros as l', 'l.id', '=', 'p.libro_id')
            ->select('p.libro_id', 'l.titulo', DB::raw('COUNT(p.id) as total'))
            ->whereDate('p.fecha_prestamo', '>=', $desde)
            ->groupBy('p.libro_id', 'l.titulo')
            ->orderByDesc('total')
            ->limit($limit)
            ->get();
    }

    public function prestamosPorGenero(int $dias = 30)
    {
        $desde = Carbon::now()->subDays($dias)->toDateString();
        return DB::table('prestamos as p')
            ->join('libros as l', 'l.id', '=', 'p.libro_id')
            ->join('genero_libro as gl', 'gl.libro_id', '=', 'l.id')
            ->join('generos as g', 'g.id', '=', 'gl.genero_id')
            ->select('g.id as genero_id', 'g.nombre', DB::raw('COUNT(p.id) as total'))
            ->whereDate('p.fecha_prestamo', '>=', $desde)
            ->groupBy('g.id', 'g.nombre')
            ->orderByDesc('total')
            ->get();
    }

    public function prestamosPorAutor(int $dias = 30)
    {
        $desde = Carbon::now()->subDays($dias)->toDateString();
        return DB::table('prestamos as p')
            ->join('libros as l', 'l.id', '=', 'p.libro_id')
            ->join('autor_libro as al', 'al.libro_id', '=', 'l.id')
            ->join('autores as a', 'a.id', '=', 'al.autor_id')
            ->select('a.id as autor_id', 'a.nombre', DB::raw('COUNT(p.id) as total'))
            ->whereDate('p.fecha_prestamo', '>=', $desde)
            ->groupBy('a.id', 'a.nombre')
            ->orderByDesc('total')
            ->get();
    }

    public function tasaTiempo(int $dias = 30): array
    {
        $desde = Carbon::now()->subDays($dias)->toDateString();
        $hoy = Carbon::today()->toDateString();

        $total = Prestamo::whereDate('fecha_prestamo', '>=', $desde)->count();

        $devueltosATiempo = Prestamo::where('estado', Prestamo::ESTADO_DEVUELTO)
            ->whereDate('fecha_prestamo', '>=', $desde)
            ->whereColumn('fecha_devolucion', '<=', 'fecha_vencimiento')
            ->count();

        $devueltosVencidos = Prestamo::where('estado', Prestamo::ESTADO_DEVUELTO)
            ->whereDate('fecha_prestamo', '>=', $desde)
            ->whereColumn('fecha_devolucion', '>', 'fecha_vencimiento')
            ->count();

        $activosVencidos = Prestamo::where('estado', Prestamo::ESTADO_ACTIVO)
            ->whereDate('fecha_prestamo', '>=', $desde)
            ->where('fecha_vencimiento', '<', $hoy)
            ->count();

        $activosEnTiempo = Prestamo::where('estado', Prestamo::ESTADO_ACTIVO)
            ->whereDate('fecha_prestamo', '>=', $desde)
            ->where('fecha_vencimiento', '>=', $hoy)
            ->count();

        return compact('total', 'devueltosATiempo', 'devueltosVencidos', 'activosVencidos', 'activosEnTiempo');
    }

    public function disponibilidadLibros()
    {
        // Para cada libro: stock, activos, disponible = max(stock - activos, 0)
        $activosPorLibro = Prestamo::select('libro_id', DB::raw('COUNT(*) as activos'))
            ->where('estado', Prestamo::ESTADO_ACTIVO)
            ->groupBy('libro_id');

        return DB::table('libros as l')
            ->leftJoinSub($activosPorLibro, 'pa', function ($join) {
                $join->on('pa.libro_id', '=', 'l.id');
            })
            ->select('l.id', 'l.titulo', 'l.stock', DB::raw('COALESCE(pa.activos,0) as activos'), DB::raw('GREATEST(l.stock - COALESCE(pa.activos,0), 0) as disponibles'))
            ->orderBy('disponibles')
            ->orderBy('l.titulo')
            ->get();
    }

    public function seriePrestamos(int $dias = 30)
    {
        $desde = Carbon::now()->subDays($dias - 1)->startOfDay();
        $hasta = Carbon::today()->endOfDay();

        $raw = Prestamo::select(DB::raw('DATE(fecha_prestamo) as fecha'), DB::raw('COUNT(*) as total'))
            ->whereBetween('fecha_prestamo', [$desde->toDateString(), $hasta->toDateString()])
            ->groupBy(DB::raw('DATE(fecha_prestamo)'))
            ->orderBy('fecha')
            ->get()
            ->keyBy('fecha');

        $serie = [];
        $cursor = $desde->copy();
        while ($cursor->lte($hasta)) {
            $key = $cursor->toDateString();
            $serie[] = [
                'fecha' => $key,
                'total' => (int) ($raw[$key]->total ?? 0),
            ];
            $cursor->addDay();
        }

        return $serie;
    }
}

