<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('genero_libro', function (Blueprint $table) {
            $table->id();
            $table->foreignId('libro_id')->constrained('libros')->cascadeOnDelete();
            $table->foreignId('genero_id')->constrained('generos')->cascadeOnDelete();
            $table->unique(['libro_id', 'genero_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('genero_libro');
    }
};

