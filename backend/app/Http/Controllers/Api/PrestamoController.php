<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PrestamoReturnRequest;
use App\Http\Requests\PrestamoStoreRequest;
use App\Http\Requests\PrestamoUpdateRequest;
use App\Models\Prestamo;
use App\Services\PrestamoService;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;

class PrestamoController extends Controller
{
    public function __construct(private readonly PrestamoService $prestamoService)
    {
    }

    #[OA\Get(path: "/api/prestamos", tags: ["Préstamos"], summary: "Listar préstamos",
        responses: [new OA\Response(response: 200, description: "OK",
            content: new OA\JsonContent(type: "array", items: new OA\Items(ref: "#/components/schemas/Prestamo"))
        )]
    )]
    public function index(\Illuminate\Http\Request $request)
    {
        $perPage = max(1, min(100, (int) $request->query('per_page', 10)));
        return response()->json(
            Prestamo::query()->latest()->paginate($perPage)
        );
    }

    #[OA\Post(path: "/api/prestamos", tags: ["Préstamos"], summary: "Crear préstamo",
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: "#/components/schemas/Prestamo")),
        responses: [new OA\Response(response: 201, description: "Creado", content: new OA\JsonContent(ref: "#/components/schemas/Prestamo"))]
    )]
    public function store(PrestamoStoreRequest $request)
    {
        $data = $request->validated();
        try {
            $prestamo = $this->prestamoService->crear($data);
            return response()->json($prestamo, Response::HTTP_CREATED);
        } catch (\DomainException $e) {
            return response()->json(['message' => $e->getMessage()], 409);
        }
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
    public function update(PrestamoUpdateRequest $request, Prestamo $prestamo)
    {
        $prestamo->fill($request->validated());
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

    #[OA\Patch(path: "/api/prestamos/{id}/devolver", tags: ["Préstamos"], summary: "Devolver préstamo",
        parameters: [new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))],
        requestBody: new OA\RequestBody(required: false, content: new OA\JsonContent(properties: [
            new OA\Property(property: "fecha_devolucion", type: "string", format: "date"),
            new OA\Property(property: "observaciones", type: "string")
        ])),
        responses: [
            new OA\Response(response: 200, description: "OK", content: new OA\JsonContent(ref: "#/components/schemas/Prestamo")),
            new OA\Response(response: 409, description: "Conflicto de negocio")
        ]
    )]
    public function devolver(PrestamoReturnRequest $request, Prestamo $prestamo)
    {
        try {
            $data = $request->validated();
            $prestamo = $this->prestamoService->devolver($prestamo, $data['fecha_devolucion'] ?? null, $data['observaciones'] ?? null);
            return response()->json($prestamo);
        } catch (\DomainException $e) {
            return response()->json(['message' => $e->getMessage()], 409);
        }
    }
}
