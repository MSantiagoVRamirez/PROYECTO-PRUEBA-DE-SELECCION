<?php

namespace Tests\Unit;

use App\Models\Libro;
use App\Models\Prestamo;
use App\Models\Usuario;
use App\Services\PrestamoService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PrestamoServiceTest extends TestCase
{
    use RefreshDatabase;

    protected PrestamoService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(PrestamoService::class);
    }

    public function test_no_puede_crear_prestamo_si_no_hay_stock(): void
    {
        $libro = Libro::create(['titulo' => 'Libro A', 'stock' => 1]);
        $u1 = Usuario::create([
            'nombre' => 'Ana', 'email' => 'ana@example.com',
            'tipo_identificacion' => 'CC', 'numero_identificacion' => '100',
        ]);
        $u2 = Usuario::create([
            'nombre' => 'Bob', 'email' => 'bob@example.com',
            'tipo_identificacion' => 'CC', 'numero_identificacion' => '101',
        ]);

        $hoy = Carbon::today()->toDateString();
        $venc = Carbon::today()->addDays(7)->toDateString();

        // Primer préstamo ocupa el único stock
        $this->service->crear([
            'usuario_id' => $u1->id,
            'libro_id' => $libro->id,
            'fecha_prestamo' => $hoy,
            'fecha_vencimiento' => $venc,
        ]);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('No hay stock disponible');

        // Segundo préstamo debería fallar por falta de stock
        $this->service->crear([
            'usuario_id' => $u2->id,
            'libro_id' => $libro->id,
            'fecha_prestamo' => $hoy,
            'fecha_vencimiento' => $venc,
        ]);
    }

    public function test_no_puede_prestamo_duplicado_mismo_usuario_y_libro(): void
    {
        $libro = Libro::create(['titulo' => 'Libro B', 'stock' => 2]);
        $u1 = Usuario::create([
            'nombre' => 'Ana', 'email' => 'ana2@example.com',
            'tipo_identificacion' => 'CC', 'numero_identificacion' => '200',
        ]);
        $hoy = Carbon::today()->toDateString();
        $venc = Carbon::today()->addDays(7)->toDateString();

        $this->service->crear([
            'usuario_id' => $u1->id,
            'libro_id' => $libro->id,
            'fecha_prestamo' => $hoy,
            'fecha_vencimiento' => $venc,
        ]);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('ya tiene un préstamo activo');

        $this->service->crear([
            'usuario_id' => $u1->id,
            'libro_id' => $libro->id,
            'fecha_prestamo' => $hoy,
            'fecha_vencimiento' => $venc,
        ]);
    }

    public function test_devolver_prestamo_activo(): void
    {
        $libro = Libro::create(['titulo' => 'Libro C', 'stock' => 1]);
        $u1 = Usuario::create([
            'nombre' => 'Ana', 'email' => 'ana3@example.com',
            'tipo_identificacion' => 'CC', 'numero_identificacion' => '300',
        ]);
        $hoy = Carbon::today()->toDateString();
        $venc = Carbon::today()->addDays(7)->toDateString();

        $prestamo = $this->service->crear([
            'usuario_id' => $u1->id,
            'libro_id' => $libro->id,
            'fecha_prestamo' => $hoy,
            'fecha_vencimiento' => $venc,
        ]);

        $this->service->devolver($prestamo, $hoy, 'ok');

        $prestamo->refresh();
        $this->assertSame(Prestamo::ESTADO_DEVUELTO, $prestamo->estado);
        $this->assertSame($hoy, $prestamo->fecha_devolucion);
    }

    public function test_no_puede_devolver_si_no_activo(): void
    {
        $libro = Libro::create(['titulo' => 'Libro D', 'stock' => 1]);
        $u1 = Usuario::create([
            'nombre' => 'Ana', 'email' => 'ana4@example.com',
            'tipo_identificacion' => 'CC', 'numero_identificacion' => '400',
        ]);
        $hoy = Carbon::today()->toDateString();
        $venc = Carbon::today()->addDays(7)->toDateString();

        $prestamo = $this->service->crear([
            'usuario_id' => $u1->id,
            'libro_id' => $libro->id,
            'fecha_prestamo' => $hoy,
            'fecha_vencimiento' => $venc,
        ]);

        // Marcamos como devuelto y luego intentamos devolver de nuevo
        $prestamo->estado = Prestamo::ESTADO_DEVUELTO;
        $prestamo->fecha_devolucion = $hoy;
        $prestamo->save();

        $this->expectException(\DomainException::class);
        $this->service->devolver($prestamo, $hoy);
    }
}

