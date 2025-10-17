<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Genero;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;

class GeneroController extends Controller
{
    #[OA\Get(path: "/api/generos", tags: ["Géneros"], summary: "Listar géneros",
        responses: [new OA\Response(response: 200, description: "OK",
            content: new OA\JsonContent(type: "array", items: new OA\Items(ref: "#/components/schemas/Genero"))
        )]
    )]
    public function index(\Illuminate\Http\Request $request)
    {
        $perPage = max(1, min(100, (int) $request->query('per_page', 10)));
        return response()->json(
            Genero::query()->latest()->paginate($perPage)
        );
    }

    #[OA\Post(path: "/api/generos", tags: ["Géneros"], summary: "Crear género",
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: "#/components/schemas/Genero")),
        responses: [new OA\Response(response: 201, description: "Creado", content: new OA\JsonContent(ref: "#/components/schemas/Genero"))]
    )]
    public function store(Request $request)
    {
        $data = $request->only([
            'nombre',
            'descripcion',
        ]);

        $genero = Genero::create($data);
        return response()->json($genero, Response::HTTP_CREATED);
    }

    #[OA\Get(path: "/api/generos/{id}", tags: ["Géneros"], summary: "Obtener género",
        parameters: [new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))],
        responses: [new OA\Response(response: 200, description: "OK", content: new OA\JsonContent(ref: "#/components/schemas/Genero"))]
    )]
    public function show(Genero $genero)
    {
        return response()->json($genero);
    }

    #[OA\Put(path: "/api/generos/{id}", tags: ["Géneros"], summary: "Actualizar género",
        parameters: [new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: "#/components/schemas/Genero")),
        responses: [new OA\Response(response: 200, description: "OK", content: new OA\JsonContent(ref: "#/components/schemas/Genero"))]
    )]
    public function update(Request $request, Genero $genero)
    {
        $data = $request->only([
            'nombre',
            'descripcion',
        ]);

        $genero->fill($data);
        $genero->save();
        return response()->json($genero);
    }

    #[OA\Delete(path: "/api/generos/{id}", tags: ["Géneros"], summary: "Eliminar género",
        parameters: [new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))],
        responses: [new OA\Response(response: 204, description: "Sin contenido")]
    )]
    public function destroy(Genero $genero)
    {
        $genero->delete();
        return response()->noContent();
    }
}
