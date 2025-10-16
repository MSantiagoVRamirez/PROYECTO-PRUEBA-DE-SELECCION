<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;

class UsuarioController extends Controller
{
    #[OA\Get(path: "/api/usuarios", tags: ["Usuarios"], summary: "Listar usuarios",
        responses: [new OA\Response(response: 200, description: "OK",
            content: new OA\JsonContent(type: "array", items: new OA\Items(ref: "#/components/schemas/Usuario"))
        )]
    )]
    public function index()
    {
        return response()->json(Usuario::query()->latest()->get());
    }

    #[OA\Post(path: "/api/usuarios", tags: ["Usuarios"], summary: "Crear usuario",
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: "#/components/schemas/Usuario")),
        responses: [new OA\Response(response: 201, description: "Creado", content: new OA\JsonContent(ref: "#/components/schemas/Usuario"))]
    )]
    public function store(Request $request)
    {
        $data = $request->only([
            'nombre',
            'email',
            'tipo_identificacion',
            'numero_identificacion',
            'telefono',
            'direccion',
            'fecha_nacimiento',
        ]);

        $usuario = Usuario::create($data);

        return response()->json($usuario, Response::HTTP_CREATED);
    }

    #[OA\Get(path: "/api/usuarios/{id}", tags: ["Usuarios"], summary: "Obtener usuario",
        parameters: [new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))],
        responses: [new OA\Response(response: 200, description: "OK", content: new OA\JsonContent(ref: "#/components/schemas/Usuario"))]
    )]
    public function show(Usuario $usuario)
    {
        return response()->json($usuario);
    }

    #[OA\Put(path: "/api/usuarios/{id}", tags: ["Usuarios"], summary: "Actualizar usuario",
        parameters: [new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: "#/components/schemas/Usuario")),
        responses: [new OA\Response(response: 200, description: "OK", content: new OA\JsonContent(ref: "#/components/schemas/Usuario"))]
    )]
    public function update(Request $request, Usuario $usuario)
    {
        $data = $request->only([
            'nombre',
            'email',
            'tipo_identificacion',
            'numero_identificacion',
            'telefono',
            'direccion',
            'fecha_nacimiento',
        ]);

        $usuario->fill($data);
        $usuario->save();

        return response()->json($usuario);
    }

    #[OA\Delete(path: "/api/usuarios/{id}", tags: ["Usuarios"], summary: "Eliminar usuario",
        parameters: [new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))],
        responses: [new OA\Response(response: 204, description: "Sin contenido")]
    )]
    public function destroy(Usuario $usuario)
    {
        $usuario->delete();
        return response()->noContent();
    }
}
