<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PrestamoStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'usuario_id' => ['required','integer','exists:usuarios,id'],
            'libro_id' => ['required','integer','exists:libros,id'],
            'fecha_prestamo' => ['required','date'],
            'fecha_vencimiento' => ['required','date','after:fecha_prestamo'],
            'fecha_devolucion' => ['nullable','date','after_or_equal:fecha_prestamo'],
            'observaciones' => ['nullable','string','max:255'],
            // estado lo maneja el servicio, no se expone aquí
        ];
    }
}

