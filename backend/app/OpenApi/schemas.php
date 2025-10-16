<?php

/**
 * @OA\Schema(schema="Usuario", type="object",
 *   required={"nombre","email","tipo_identificacion","numero_identificacion"},
 *   @OA\Property(property="id", type="integer", format="int64"),
 *   @OA\Property(property="nombre", type="string"),
 *   @OA\Property(property="email", type="string"),
 *   @OA\Property(property="tipo_identificacion", type="string"),
 *   @OA\Property(property="numero_identificacion", type="string"),
 *   @OA\Property(property="telefono", type="string", nullable=true),
 *   @OA\Property(property="direccion", type="string", nullable=true),
 *   @OA\Property(property="fecha_nacimiento", type="string", format="date", nullable=true),
 *   @OA\Property(property="created_at", type="string", format="date-time"),
 *   @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 * @OA\Schema(schema="Autor", type="object",
 *   required={"nombre"},
 *   @OA\Property(property="id", type="integer", format="int64"),
 *   @OA\Property(property="nombre", type="string"),
 *   @OA\Property(property="nacionalidad", type="string", nullable=true),
 *   @OA\Property(property="fecha_nacimiento", type="string", format="date", nullable=true),
 *   @OA\Property(property="fecha_fallecimiento", type="string", format="date", nullable=true),
 *   @OA\Property(property="biografia", type="string", nullable=true),
 *   @OA\Property(property="created_at", type="string", format="date-time"),
 *   @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 * @OA\Schema(schema="Genero", type="object",
 *   required={"nombre"},
 *   @OA\Property(property="id", type="integer", format="int64"),
 *   @OA\Property(property="nombre", type="string"),
 *   @OA\Property(property="descripcion", type="string", nullable=true),
 *   @OA\Property(property="created_at", type="string", format="date-time"),
 *   @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 * @OA\Schema(schema="Libro", type="object",
 *   required={"titulo","stock"},
 *   @OA\Property(property="id", type="integer", format="int64"),
 *   @OA\Property(property="titulo", type="string"),
 *   @OA\Property(property="resumen", type="string", nullable=true),
 *   @OA\Property(property="anio_publicacion", type="integer", nullable=true),
 *   @OA\Property(property="isbn", type="string", nullable=true),
 *   @OA\Property(property="stock", type="integer"),
 *   @OA\Property(property="created_at", type="string", format="date-time"),
 *   @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 * @OA\Schema(schema="Prestamo", type="object",
 *   required={"usuario_id","libro_id","fecha_prestamo","fecha_vencimiento"},
 *   @OA\Property(property="id", type="integer", format="int64"),
 *   @OA\Property(property="usuario_id", type="integer"),
 *   @OA\Property(property="libro_id", type="integer"),
 *   @OA\Property(property="fecha_prestamo", type="string", format="date"),
 *   @OA\Property(property="fecha_vencimiento", type="string", format="date"),
 *   @OA\Property(property="fecha_devolucion", type="string", format="date", nullable=true),
 *   @OA\Property(property="estado", type="string", enum={"activo","devuelto","vencido"}),
 *   @OA\Property(property="observaciones", type="string", nullable=true),
 *   @OA\Property(property="created_at", type="string", format="date-time"),
 *   @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */

