<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Libro;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;

class LibroController extends Controller
{
    #[OA\Get(path: "/api/libros", tags: ["Libros"], summary: "Listar libros",
        responses: [new OA\Response(response: 200, description: "OK",
            content: new OA\JsonContent(type: "array", items: new OA\Items(ref: "#/components/schemas/Libro"))
        )]
    )]
    public function index()
    {
        return response()->json(
            Libro::query()->latest()->get()
        );
    }

    #[OA\Post(path: "/api/libros", tags: ["Libros"], summary: "Crear libro",
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: "#/components/schemas/Libro")),
        responses: [new OA\Response(response: 201, description: "Creado", content: new OA\JsonContent(ref: "#/components/schemas/Libro"))]
    )]
    public function store(Request $request)
    {
        $data = $request->only([
            'titulo',
            'resumen',
            'anio_publicacion',
            'isbn',
            'stock',
        ]);

        $libro = Libro::create($data);
        return response()->json($libro, Response::HTTP_CREATED);
    }

    #[OA\Get(path: "/api/libros/{id}", tags: ["Libros"], summary: "Obtener libro",
        parameters: [new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))],
        responses: [new OA\Response(response: 200, description: "OK", content: new OA\JsonContent(ref: "#/components/schemas/Libro"))]
    )]
    public function show(Libro $libro)
    {
        return response()->json($libro);
    }

    #[OA\Put(path: "/api/libros/{id}", tags: ["Libros"], summary: "Actualizar libro",
        parameters: [new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: "#/components/schemas/Libro")),
        responses: [new OA\Response(response: 200, description: "OK", content: new OA\JsonContent(ref: "#/components/schemas/Libro"))]
    )]
    public function update(Request $request, Libro $libro)
    {
        $data = $request->only([
            'titulo',
            'resumen',
            'anio_publicacion',
            'isbn',
            'stock',
        ]);

        $libro->fill($data);
        $libro->save();
        return response()->json($libro);
    }

    #[OA\Delete(path: "/api/libros/{id}", tags: ["Libros"], summary: "Eliminar libro",
        parameters: [new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))],
        responses: [new OA\Response(response: 204, description: "Sin contenido")]
    )]
    public function destroy(Libro $libro)
    {
        $libro->delete();
        return response()->noContent();
    }
}
