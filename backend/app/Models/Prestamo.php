<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prestamo extends Model
{
    use HasFactory;

    protected $table = 'prestamos';

    public const ESTADO_ACTIVO = 'activo';
    public const ESTADO_DEVUELTO = 'devuelto';
    public const ESTADO_VENCIDO = 'vencido';

    protected $fillable = [
        'usuario_id',
        'libro_id',
        'fecha_prestamo',
        'fecha_vencimiento',
        'fecha_devolucion',
        'estado',
        'observaciones',
    ];

    protected $casts = [
        'fecha_prestamo' => 'date',
        'fecha_vencimiento' => 'date',
        'fecha_devolucion' => 'date',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function libro(): BelongsTo
    {
        return $this->belongsTo(Libro::class, 'libro_id');
    }
}

