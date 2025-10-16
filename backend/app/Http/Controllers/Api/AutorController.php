<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Autor;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;

class AutorController extends Controller
{
    #[OA\Get(path: "/api/autores", tags: ["Autores"], summary: "Listar autores",
        responses: [new OA\Response(response: 200, description: "OK",
            content: new OA\JsonContent(type: "array", items: new OA\Items(ref: "#/components/schemas/Autor"))
        )]
    )]
    public function index()
    {
        return response()->json(Autor::query()->latest()->get());
    }

    #[OA\Post(path: "/api/autores", tags: ["Autores"], summary: "Crear autor",
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: "#/components/schemas/Autor")),
        responses: [new OA\Response(response: 201, description: "Creado", content: new OA\JsonContent(ref: "#/components/schemas/Autor"))]
    )]
    public function store(Request $request)
    {
        $data = $request->only([
            'nombre',
            'nacionalidad',
            'fecha_nacimiento',
            'fecha_fallecimiento',
            'biografia',
        ]);

        $autor = Autor::create($data);
        return response()->json($autor, Response::HTTP_CREATED);
    }

    #[OA\Get(path: "/api/autores/{id}", tags: ["Autores"], summary: "Obtener autor",
        parameters: [new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))],
        responses: [new OA\Response(response: 200, description: "OK", content: new OA\JsonContent(ref: "#/components/schemas/Autor"))]
    )]
    public function show(Autor $autore)
    {
        // Nota: para apiResource('autores'), el parámetro se llama {autore}
        return response()->json($autore);
    }

    #[OA\Put(path: "/api/autores/{id}", tags: ["Autores"], summary: "Actualizar autor",
        parameters: [new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: "#/components/schemas/Autor")),
        responses: [new OA\Response(response: 200, description: "OK", content: new OA\JsonContent(ref: "#/components/schemas/Autor"))]
    )]
    public function update(Request $request, Autor $autore)
    {
        $data = $request->only([
            'nombre',
            'nacionalidad',
            'fecha_nacimiento',
            'fecha_fallecimiento',
            'biografia',
        ]);

        $autore->fill($data);
        $autore->save();
        return response()->json($autore);
    }

    #[OA\Delete(path: "/api/autores/{id}", tags: ["Autores"], summary: "Eliminar autor",
        parameters: [new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))],
        responses: [new OA\Response(response: 204, description: "Sin contenido")]
    )]
    public function destroy(Autor $autore)
    {
        $autore->delete();
        return response()->noContent();
    }
}
