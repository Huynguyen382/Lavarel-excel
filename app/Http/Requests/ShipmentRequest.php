<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShipmentRequest extends FormRequest
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
            'e1_code' => 'required|string|max:255|unique:oneships,e1_code,' . $this->route('id'),
            'release_date' => 'nullable|date',
            'chargeable_volumn' => 'nullable|string|max:255',
            'main_charge' => 'nullable|string|max:255',
            'receiver' => 'nullable|string|max:255',
            'recipient_address' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'reference_number' => 'nullable|string|max:255',
            'file_name' => 'nullable|string|max:255',
            'carrier_id' => 'nullable|exists:carriers,id'
        ];
    }

    public function messages()
    {
        return [
            'type.required' => 'The type field is required.',
            'type.in' => 'The selected type is invalid.',
            'e1_code.required' => 'The e1_code field is required.',
            'e1_code.max' => 'The e1_code may not be greater than 255 characters.',
            'e1_code.unique' => 'The e1_code has already been taken.',
            'release_date.date' => 'The release_date must be a valid date.',
            'chargeable_volumn.max' => 'The chargeable_volumn may not be greater than 255 characters.',
            'main_charge.max' => 'The main_charge may not be greater than 255 characters.',
           'receiver.max' => 'The receiver may not be greater than 255 characters.',
        ];
    }
}
