<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OneshipRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize():bool
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
            'e1_code' => 'required|string|unique:oneships,e1_code,' . $this->route('oneship'),
            'release_date' => 'required|date',
            'chargeable_volumn' => 'nullable|numeric',
            'main_charge' => 'nullable|numeric',
            'receiver' => 'required|string',
            'recipient_address' => 'required|string',
            'phone_number' => 'nullable|string',
            'reference_number' => 'nullable|string',
            'file_name' => 'nullable|string',
        ];
    }
}
