<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
{
    /**
     * Determinar si el usuario está autorizado para hacer esta solicitud.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Obtener las reglas de validación que se aplican a la solicitud.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_name' => ['required', 'string', 'max:255'],
            'total_amount' => ['required', 'numeric', 'min:0.01', 'max:999999.99'],
        ];
    }

    /**
     * Mensajes de error personalizados.
     */
    public function messages(): array
    {
        return [
            'customer_name.required' => 'El nombre del cliente es obligatorio.',
            'customer_name.string' => 'El nombre del cliente debe ser texto.',
            'customer_name.max' => 'El nombre del cliente no puede exceder 255 caracteres.',
            'total_amount.required' => 'El monto total es obligatorio.',
            'total_amount.numeric' => 'El monto total debe ser un número.',
            'total_amount.min' => 'El monto total debe ser mayor a 0.',
            'total_amount.max' => 'El monto total no puede exceder 999999.99.',
        ];
    }
}
