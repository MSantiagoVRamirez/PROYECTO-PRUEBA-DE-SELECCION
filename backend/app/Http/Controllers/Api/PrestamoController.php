<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Prestamo;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;

class PrestamoController extends Controller
{
    #[OA\Get(path: "/api/prestamos", tags: ["Préstamos"], summary: "Listar préstamos",
        responses: [new OA\Response(response: 200, description: "OK",
            content: new OA\JsonContent(type: "array", items: new OA\Items(ref: "#/components/schemas/Prestamo"))
        )]
    )]
    public function index()
    {
        return response()->json(
            Prestamo::query()->latest()->get()
        );
    }

    #[OA\Post(path: "/api/prestamos", tags: ["Préstamos"], summary: "Crear préstamo",
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: "#/components/schemas/Prestamo")),
        responses: [new OA\Response(response: 201, description: "Creado", content: new OA\JsonContent(ref: "#/components/schemas/Prestamo"))]
    )]
    public function store(Request $request)
    {
        $data = $request->only([
            'usuario_id',
            'libro_id',
            'fecha_prestamo',
            'fecha_vencimiento',
            'fecha_devolucion',
            'estado',
            'observaciones',
        ]);

        $prestamo = Prestamo::create($data);
        return response()->json($prestamo, Response::HTTP_CREATED);
    }

    #[OA\Get(path: "/api/prestamos/{id}", tags: ["Préstamos"], summary: "Obtener préstamo",
        parameters: [new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))],
        responses: [new OA\Response(response: 200, description: "OK", content: new OA\JsonContent(ref: "#/components/schemas/Prestamo"))]
    )]
    public function show(Prestamo $prestamo)
    {
        return response()->json($prestamo);
    }

    #[OA\Put(path: "/api/prestamos/{id}", tags: ["Préstamos"], summary: "Actualizar préstamo",
        parameters: [new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: "#/components/schemas/Prestamo")),
        responses: [new OA\Response(response: 200, description: "OK", content: new OA\JsonContent(ref: "#/components/schemas/Prestamo"))]
    )]
    public function update(Request $request, Prestamo $prestamo)
    {
        $data = $request->only([
            'usuario_id',
            'libro_id',
            'fecha_prestamo',
            'fecha_vencimiento',
            'fecha_devolucion',
            'estado',
            'observaciones',
        ]);

        $prestamo->fill($data);
        $prestamo->save();
        return response()->json($prestamo);
    }

    #[OA\Delete(path: "/api/prestamos/{id}", tags: ["Préstamos"], summary: "Eliminar préstamo",
        parameters: [new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))],
        responses: [new OA\Response(response: 204, description: "Sin contenido")]
    )]
    public function destroy(Prestamo $prestamo)
    {
        $prestamo->delete();
        return response()->noContent();
    }
}
