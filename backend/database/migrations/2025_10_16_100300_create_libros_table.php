<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('libros', function (Blueprint $table) {
            $table->id();
            $table->string('titulo', 200);
            $table->text('resumen')->nullable();
            $table->unsignedSmallInteger('anio_publicacion')->nullable();
            $table->string('isbn', 20)->nullable()->unique();
            $table->unsignedSmallInteger('stock')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('libros');
    }
};

