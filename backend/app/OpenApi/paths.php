<?php

/**
 * Usuarios
 * @OA\PathItem(path="/api/usuarios",
 *   @OA\Get(tags={"Usuarios"}, summary="Listar usuarios",
 *     @OA\Response(response=200, description="OK",
 *       @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Usuario"))
 *     )
 *   ),
 *   @OA\Post(tags={"Usuarios"}, summary="Crear usuario",
 *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/Usuario")),
 *     @OA\Response(response=201, description="Creado", @OA\JsonContent(ref="#/components/schemas/Usuario"))
 *   )
 * )
 * @OA\PathItem(path="/api/usuarios/{id}",
 *   @OA\Get(tags={"Usuarios"}, summary="Obtener usuario",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/Usuario"))
 *   ),
 *   @OA\Put(tags={"Usuarios"}, summary="Actualizar usuario",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/Usuario")),
 *     @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/Usuario"))
 *   ),
 *   @OA\Delete(tags={"Usuarios"}, summary="Eliminar usuario",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=204, description="Sin contenido")
 *   )
 * )
 */

/**
 * Autores
 * @OA\PathItem(path="/api/autores",
 *   @OA\Get(tags={"Autores"}, summary="Listar autores",
 *     @OA\Response(response=200, description="OK",
 *       @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Autor"))
 *     )
 *   ),
 *   @OA\Post(tags={"Autores"}, summary="Crear autor",
 *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/Autor")),
 *     @OA\Response(response=201, description="Creado", @OA\JsonContent(ref="#/components/schemas/Autor"))
 *   )
 * )
 * @OA\PathItem(path="/api/autores/{id}",
 *   @OA\Get(tags={"Autores"}, summary="Obtener autor",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/Autor"))
 *   ),
 *   @OA\Put(tags={"Autores"}, summary="Actualizar autor",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/Autor")),
 *     @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/Autor"))
 *   ),
 *   @OA\Delete(tags={"Autores"}, summary="Eliminar autor",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=204, description="Sin contenido")
 *   )
 * )
 */

/**
 * Géneros
 * @OA\PathItem(path="/api/generos",
 *   @OA\Get(tags={"Géneros"}, summary="Listar géneros",
 *     @OA\Response(response=200, description="OK",
 *       @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Genero"))
 *     )
 *   ),
 *   @OA\Post(tags={"Géneros"}, summary="Crear género",
 *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/Genero")),
 *     @OA\Response(response=201, description="Creado", @OA\JsonContent(ref="#/components/schemas/Genero"))
 *   )
 * )
 * @OA\PathItem(path="/api/generos/{id}",
 *   @OA\Get(tags={"Géneros"}, summary="Obtener género",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/Genero"))
 *   ),
 *   @OA\Put(tags={"Géneros"}, summary="Actualizar género",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/Genero")),
 *     @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/Genero"))
 *   ),
 *   @OA\Delete(tags={"Géneros"}, summary="Eliminar género",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=204, description="Sin contenido")
 *   )
 * )
 */

/**
 * Libros
 * @OA\PathItem(path="/api/libros",
 *   @OA\Get(tags={"Libros"}, summary="Listar libros",
 *     @OA\Response(response=200, description="OK",
 *       @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Libro"))
 *     )
 *   ),
 *   @OA\Post(tags={"Libros"}, summary="Crear libro",
 *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/Libro")),
 *     @OA\Response(response=201, description="Creado", @OA\JsonContent(ref="#/components/schemas/Libro"))
 *   )
 * )
 * @OA\PathItem(path="/api/libros/{id}",
 *   @OA\Get(tags={"Libros"}, summary="Obtener libro",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/Libro"))
 *   ),
 *   @OA\Put(tags={"Libros"}, summary="Actualizar libro",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/Libro")),
 *     @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/Libro"))
 *   ),
 *   @OA\Delete(tags={"Libros"}, summary="Eliminar libro",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=204, description="Sin contenido")
 *   )
 * )
 */

/**
 * Préstamos
 * @OA\PathItem(path="/api/prestamos",
 *   @OA\Get(tags={"Préstamos"}, summary="Listar préstamos",
 *     @OA\Response(response=200, description="OK",
 *       @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Prestamo"))
 *     )
 *   ),
 *   @OA\Post(tags={"Préstamos"}, summary="Crear préstamo",
 *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/Prestamo")),
 *     @OA\Response(response=201, description="Creado", @OA\JsonContent(ref="#/components/schemas/Prestamo"))
 *   )
 * )
 * @OA\PathItem(path="/api/prestamos/{id}",
 *   @OA\Get(tags={"Préstamos"}, summary="Obtener préstamo",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/Prestamo"))
 *   ),
 *   @OA\Put(tags={"Préstamos"}, summary="Actualizar préstamo",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/Prestamo")),
 *     @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/Prestamo"))
 *   ),
 *   @OA\Delete(tags={"Préstamos"}, summary="Eliminar préstamo",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=204, description="Sin contenido")
 *   )
 * )
 */

