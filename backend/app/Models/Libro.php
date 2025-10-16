<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Libro extends Model
{
    use HasFactory;

    protected $table = 'libros';

    protected $fillable = [
        'titulo',
        'resumen',
        'anio_publicacion',
        'isbn',
        'stock',
    ];

    protected $casts = [
        'anio_publicacion' => 'integer',
        'stock' => 'integer',
    ];

    public function autores(): BelongsToMany
    {
        return $this->belongsToMany(Autor::class, 'autor_libro', 'libro_id', 'autor_id')
            ->withTimestamps();
    }

    public function generos(): BelongsToMany
    {
        return $this->belongsToMany(Genero::class, 'genero_libro', 'libro_id', 'genero_id')
            ->withTimestamps();
    }

    public function prestamos(): HasMany
    {
        return $this->hasMany(Prestamo::class, 'libro_id');
    }
}

