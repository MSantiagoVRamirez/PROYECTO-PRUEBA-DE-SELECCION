<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Genero extends Model
{
    use HasFactory;

    protected $table = 'generos';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    public function libros(): BelongsToMany
    {
        return $this->belongsToMany(Libro::class, 'genero_libro', 'genero_id', 'libro_id')
            ->withTimestamps();
    }
}

