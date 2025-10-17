<?php

namespace App\Services;

use App\Models\Libro;
use App\Models\Prestamo;
use Illuminate\Support\Facades\DB;
use DomainException;
use Carbon\Carbon;

class PrestamoService
{
    public function crear(array $data): Prestamo
    {
        return DB::transaction(function () use ($data) {
            // Bloqueo pesimista del libro para evitar carreras
            $libro = Libro::query()->lockForUpdate()->findOrFail($data['libro_id']);

            $activos = Prestamo::query()
                ->where('libro_id', $libro->id)
                ->where('estado', Prestamo::ESTADO_ACTIVO)
                ->count();

            if ($activos >= $libro->stock) {
                throw new DomainException('No hay stock disponible para este libro.');
            }

            // Evitar préstamo activo duplicado del mismo libro por el mismo usuario
            $duplicado = Prestamo::query()
                ->where('libro_id', $libro->id)
                ->where('usuario_id', $data['usuario_id'])
                ->where('estado', Prestamo::ESTADO_ACTIVO)
                ->exists();

            if ($duplicado) {
                throw new DomainException('El usuario ya tiene un préstamo activo de este libro.');
            }

            $prestamo = new Prestamo();
            $prestamo->usuario_id = $data['usuario_id'];
            $prestamo->libro_id = $libro->id;
            $prestamo->fecha_prestamo = Carbon::parse($data['fecha_prestamo']);
            $prestamo->fecha_vencimiento = Carbon::parse($data['fecha_vencimiento']);
            $prestamo->fecha_devolucion = $data['fecha_devolucion'] ?? null;
            $prestamo->estado = Prestamo::ESTADO_ACTIVO;
            $prestamo->observaciones = $data['observaciones'] ?? null;
            $prestamo->save();

            return $prestamo->fresh();
        });
    }

    public function devolver(Prestamo $prestamo, ?string $fechaDevolucion = null, ?string $observaciones = null): Prestamo
    {
        return DB::transaction(function () use ($prestamo, $fechaDevolucion, $observaciones) {
            if ($prestamo->estado !== Prestamo::ESTADO_ACTIVO) {
                throw new DomainException('Solo se pueden devolver préstamos activos.');
            }

            $fecha = $fechaDevolucion ? Carbon::parse($fechaDevolucion) : Carbon::now();
            if ($fecha->lt(Carbon::parse($prestamo->fecha_prestamo))) {
                throw new DomainException('La fecha de devolución no puede ser anterior a la fecha de préstamo.');
            }

            $prestamo->fecha_devolucion = $fecha->toDateString();
            $prestamo->estado = Prestamo::ESTADO_DEVUELTO;
            if ($observaciones !== null) {
                $prestamo->observaciones = $observaciones;
            }
            $prestamo->save();

            return $prestamo->fresh();
        });
    }
}

