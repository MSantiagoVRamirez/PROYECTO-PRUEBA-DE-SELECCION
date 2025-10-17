<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PrestamoReturnRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fecha_devolucion' => ['nullable','date'],
            'observaciones' => ['nullable','string','max:255'],
        ];
    }
}

