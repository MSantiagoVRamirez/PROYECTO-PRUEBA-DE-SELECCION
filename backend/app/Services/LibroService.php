<?php

namespace App\Services;

use App\Models\Libro;
use Illuminate\Support\Facades\DB;

class LibroService
{
    public function crear(array $data): Libro
    {
        $autores = $data['autores'] ?? [];
        $generos = $data['generos'] ?? [];
        unset($data['autores'], $data['generos']);

        return DB::transaction(function () use ($data, $autores, $generos) {
            $libro = Libro::create($data);
            if (!empty($autores)) {
                $libro->autores()->sync($autores);
            }
            if (!empty($generos)) {
                $libro->generos()->sync($generos);
            }
            return $libro->fresh();
        });
    }

    public function actualizar(Libro $libro, array $data): Libro
    {
        $autores = $data['autores'] ?? null;
        $generos = $data['generos'] ?? null;
        unset($data['autores'], $data['generos']);

        return DB::transaction(function () use ($libro, $data, $autores, $generos) {
            $libro->fill($data);
            $libro->save();
            if (is_array($autores)) {
                $libro->autores()->sync($autores);
            }
            if (is_array($generos)) {
                $libro->generos()->sync($generos);
            }
            return $libro->fresh();
        });
    }
}

