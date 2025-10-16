<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\UsuarioController;
use App\Http\Controllers\Api\AutorController;
use App\Http\Controllers\Api\GeneroController;
use App\Http\Controllers\Api\LibroController;
use App\Http\Controllers\Api\PrestamoController;

Route::apiResource('usuarios', UsuarioController::class);
Route::apiResource('autores', AutorController::class);
Route::apiResource('generos', GeneroController::class);
Route::apiResource('libros', LibroController::class);
Route::apiResource('prestamos', PrestamoController::class);

