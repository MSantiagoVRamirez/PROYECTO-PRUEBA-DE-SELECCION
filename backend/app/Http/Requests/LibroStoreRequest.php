<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LibroStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $currentYear = (int) date('Y');
        return [
            'titulo' => ['required','string','max:200'],
            'resumen' => ['nullable','string'],
            'anio_publicacion' => ['nullable','integer','between:1450,'.$currentYear],
            'isbn' => ['nullable','string','max:20', Rule::unique('libros','isbn')],
            'stock' => ['required','integer','min:0'],
            'autores' => ['nullable','array'],
            'autores.*' => ['integer','distinct','exists:autores,id'],
            'generos' => ['nullable','array'],
            'generos.*' => ['integer','distinct','exists:generos,id'],
        ];
    }
}

