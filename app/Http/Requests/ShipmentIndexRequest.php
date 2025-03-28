<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShipmentIndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type' => 'required|in:oneships,vnpost',
            // 'limit' => 'integer|min:1|max:1000',
            // 'page' => 'integer|min:1'
        ];
    }
    public function messages(): array
    {
        return [
            'type.required' => 'Please select shipment type',
            'type.in' => 'Selected shipment type is invalid'
        ];
    }
}
