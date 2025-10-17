<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LibroStoreRequest;
use App\Http\Requests\LibroUpdateRequest;
use App\Models\Libro;
use App\Services\LibroService;
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
    public function index(\Illuminate\Http\Request $request)
    {
        $perPage = max(1, min(100, (int) $request->query('per_page', 10)));
        return response()->json(
            Libro::query()->latest()->paginate($perPage)
        );
    }

    #[OA\Post(path: "/api/libros", tags: ["Libros"], summary: "Crear libro",
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: "#/components/schemas/Libro")),
        responses: [new OA\Response(response: 201, description: "Creado", content: new OA\JsonContent(ref: "#/components/schemas/Libro"))]
    )]
    public function __construct(private readonly LibroService $libroService)
    {
    }

    public function store(LibroStoreRequest $request)
    {
        $data = $request->validated();
        $libro = $this->libroService->crear($data);
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
    public function update(LibroUpdateRequest $request, Libro $libro)
    {
        $data = $request->validated();
        $libro = $this->libroService->actualizar($libro, $data);
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
