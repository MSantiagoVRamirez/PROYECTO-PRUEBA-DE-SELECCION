<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\UsuarioController;
use App\Http\Controllers\Api\AutorController;
use App\Http\Controllers\Api\GeneroController;
use App\Http\Controllers\Api\LibroController;
use App\Http\Controllers\Api\PrestamoController;
use App\Http\Controllers\Api\EstadisticaController;

Route::apiResource('usuarios', UsuarioController::class);
Route::apiResource('autores', AutorController::class);
Route::apiResource('generos', GeneroController::class);
Route::apiResource('libros', LibroController::class);
Route::apiResource('prestamos', PrestamoController::class);
Route::patch('prestamos/{prestamo}/devolver', [PrestamoController::class, 'devolver']);

// Estadísticas
Route::prefix('estadisticas')->group(function () {
    Route::get('overview', [EstadisticaController::class, 'overview']);
    Route::get('top-libros', [EstadisticaController::class, 'topLibros']);
    Route::get('prestamos-por-genero', [EstadisticaController::class, 'prestamosPorGenero']);
    Route::get('prestamos-por-autor', [EstadisticaController::class, 'prestamosPorAutor']);
    Route::get('tasa-tiempo', [EstadisticaController::class, 'tasaTiempo']);
    Route::get('disponibilidad-libros', [EstadisticaController::class, 'disponibilidadLibros']);
    Route::get('serie-prestamos', [EstadisticaController::class, 'seriePrestamos']);
});
