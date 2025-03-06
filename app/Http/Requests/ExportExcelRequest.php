<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExportExcelRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'file' => 'required|mimes:xlsx,xls|max:5120',
        ];
    }
    public function messages(){
        return [
            'file.required' => 'Please select an Excel file to export.',
            'file.mimes' => 'The selected file must be an Excel file.',
            'file.max' => 'The selected file size is too large. Please upload a file with a maximum size of 5MB.',
        ];
    }
}
