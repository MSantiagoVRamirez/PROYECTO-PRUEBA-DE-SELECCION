<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prestamos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('usuarios')->restrictOnDelete();
            $table->foreignId('libro_id')->constrained('libros')->restrictOnDelete();
            $table->date('fecha_prestamo');
            $table->date('fecha_vencimiento');
            $table->date('fecha_devolucion')->nullable();
            $table->enum('estado', ['activo', 'devuelto', 'vencido'])->default('activo');
            $table->string('observaciones', 255)->nullable();
            $table->timestamps();

            $table->index('libro_id');
            $table->index('usuario_id');
            $table->index('estado');
            $table->index('fecha_vencimiento');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prestamos');
    }
};

