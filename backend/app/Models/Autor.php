<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Autor extends Model
{
    use HasFactory;

    protected $table = 'autores';

    protected $fillable = [
        'nombre',
        'nacionalidad',
        'fecha_nacimiento',
        'fecha_fallecimiento',
        'biografia',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'fecha_fallecimiento' => 'date',
    ];

    public function libros(): BelongsToMany
    {
        return $this->belongsToMany(Libro::class, 'autor_libro', 'autor_id', 'libro_id')
            ->withTimestamps();
    }
}

