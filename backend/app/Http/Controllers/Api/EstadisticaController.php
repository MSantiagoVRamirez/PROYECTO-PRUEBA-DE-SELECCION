<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\EstadisticasService;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class EstadisticaController extends Controller
{
    public function __construct(private readonly EstadisticasService $service)
    {
    }

    #[OA\Get(path: "/api/estadisticas/overview", tags: ["Estadísticas"], summary: "Resumen general",
        parameters: [
            new OA\Parameter(name: "dias", in: "query", schema: new OA\Schema(type: "integer", default: 30))
        ],
        responses: [new OA\Response(response: 200, description: "OK")]
    )]
    public function overview(Request $request)
    {
        $dias = (int) $request->query('dias', 30);
        return response()->json($this->service->overview($dias));
    }

    #[OA\Get(path: "/api/estadisticas/top-libros", tags: ["Estadísticas"], summary: "Top de libros por préstamos",
        parameters: [
            new OA\Parameter(name: "dias", in: "query", schema: new OA\Schema(type: "integer", default: 30)),
            new OA\Parameter(name: "limit", in: "query", schema: new OA\Schema(type: "integer", default: 5)),
        ],
        responses: [new OA\Response(response: 200, description: "OK")]
    )]
    public function topLibros(Request $request)
    {
        $dias = (int) $request->query('dias', 30);
        $limit = (int) $request->query('limit', 5);
        return response()->json($this->service->topLibros($dias, $limit));
    }

    #[OA\Get(path: "/api/estadisticas/prestamos-por-genero", tags: ["Estadísticas"], summary: "Préstamos agrupados por género",
        parameters: [new OA\Parameter(name: "dias", in: "query", schema: new OA\Schema(type: "integer", default: 30))],
        responses: [new OA\Response(response: 200, description: "OK")]
    )]
    public function prestamosPorGenero(Request $request)
    {
        $dias = (int) $request->query('dias', 30);
        return response()->json($this->service->prestamosPorGenero($dias));
    }

    #[OA\Get(path: "/api/estadisticas/prestamos-por-autor", tags: ["Estadísticas"], summary: "Préstamos agrupados por autor",
        parameters: [new OA\Parameter(name: "dias", in: "query", schema: new OA\Schema(type: "integer", default: 30))],
        responses: [new OA\Response(response: 200, description: "OK")]
    )]
    public function prestamosPorAutor(Request $request)
    {
        $dias = (int) $request->query('dias', 30);
        return response()->json($this->service->prestamosPorAutor($dias));
    }

    #[OA\Get(path: "/api/estadisticas/tasa-tiempo", tags: ["Estadísticas"], summary: "Tasas de devolución a tiempo / vencido",
        parameters: [new OA\Parameter(name: "dias", in: "query", schema: new OA\Schema(type: "integer", default: 30))],
        responses: [new OA\Response(response: 200, description: "OK")]
    )]
    public function tasaTiempo(Request $request)
    {
        $dias = (int) $request->query('dias', 30);
        return response()->json($this->service->tasaTiempo($dias));
    }

    #[OA\Get(path: "/api/estadisticas/disponibilidad-libros", tags: ["Estadísticas"], summary: "Disponibilidad actual por libro",
        responses: [new OA\Response(response: 200, description: "OK")]
    )]
    public function disponibilidadLibros()
    {
        return response()->json($this->service->disponibilidadLibros());
    }

    #[OA\Get(path: "/api/estadisticas/serie-prestamos", tags: ["Estadísticas"], summary: "Serie diaria de préstamos",
        parameters: [new OA\Parameter(name: "dias", in: "query", schema: new OA\Schema(type: "integer", default: 30))],
        responses: [new OA\Response(response: 200, description: "OK")]
    )]
    public function seriePrestamos(Request $request)
    {
        $dias = (int) $request->query('dias', 30);
        return response()->json($this->service->seriePrestamos($dias));
    }
}

