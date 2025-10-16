<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Info(title: "API Biblioteca", version: "1.0.0", description: "Documentación de la API del sistema de biblioteca")]
#[OA\Server(url: "http://127.0.0.1:8000", description: "Entorno local")]
#[\AllowDynamicProperties]
class Components
{
    #[OA\Schema(
        schema: 'Usuario', type: 'object', required: ['nombre','email','tipo_identificacion','numero_identificacion'],
        properties: [
            new OA\Property(property: 'id', type: 'integer', format: 'int64'),
            new OA\Property(property: 'nombre', type: 'string'),
            new OA\Property(property: 'email', type: 'string'),
            new OA\Property(property: 'tipo_identificacion', type: 'string'),
            new OA\Property(property: 'numero_identificacion', type: 'string'),
            new OA\Property(property: 'telefono', type: 'string', nullable: true),
            new OA\Property(property: 'direccion', type: 'string', nullable: true),
            new OA\Property(property: 'fecha_nacimiento', type: 'string', format: 'date', nullable: true),
            new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
            new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
        ]
    )]
    public $usuario;

    #[OA\Schema(
        schema: 'Autor', type: 'object', required: ['nombre'],
        properties: [
            new OA\Property(property: 'id', type: 'integer', format: 'int64'),
            new OA\Property(property: 'nombre', type: 'string'),
            new OA\Property(property: 'nacionalidad', type: 'string', nullable: true),
            new OA\Property(property: 'fecha_nacimiento', type: 'string', format: 'date', nullable: true),
            new OA\Property(property: 'fecha_fallecimiento', type: 'string', format: 'date', nullable: true),
            new OA\Property(property: 'biografia', type: 'string', nullable: true),
            new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
            new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
        ]
    )]
    public $autor;

    #[OA\Schema(
        schema: 'Genero', type: 'object', required: ['nombre'],
        properties: [
            new OA\Property(property: 'id', type: 'integer', format: 'int64'),
            new OA\Property(property: 'nombre', type: 'string'),
            new OA\Property(property: 'descripcion', type: 'string', nullable: true),
            new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
            new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
        ]
    )]
    public $genero;

    #[OA\Schema(
        schema: 'Libro', type: 'object', required: ['titulo','stock'],
        properties: [
            new OA\Property(property: 'id', type: 'integer', format: 'int64'),
            new OA\Property(property: 'titulo', type: 'string'),
            new OA\Property(property: 'resumen', type: 'string', nullable: true),
            new OA\Property(property: 'anio_publicacion', type: 'integer', nullable: true),
            new OA\Property(property: 'isbn', type: 'string', nullable: true),
            new OA\Property(property: 'stock', type: 'integer'),
            new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
            new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
        ]
    )]
    public $libro;

    #[OA\Schema(
        schema: 'Prestamo', type: 'object', required: ['usuario_id','libro_id','fecha_prestamo','fecha_vencimiento'],
        properties: [
            new OA\Property(property: 'id', type: 'integer', format: 'int64'),
            new OA\Property(property: 'usuario_id', type: 'integer'),
            new OA\Property(property: 'libro_id', type: 'integer'),
            new OA\Property(property: 'fecha_prestamo', type: 'string', format: 'date'),
            new OA\Property(property: 'fecha_vencimiento', type: 'string', format: 'date'),
            new OA\Property(property: 'fecha_devolucion', type: 'string', format: 'date', nullable: true),
            new OA\Property(property: 'estado', type: 'string', enum: ['activo','devuelto','vencido']),
            new OA\Property(property: 'observaciones', type: 'string', nullable: true),
            new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
            new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
        ]
    )]
    public $prestamo;
}

