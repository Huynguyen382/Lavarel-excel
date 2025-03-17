<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportExcelRequest extends FormRequest
{
    public function authorize():bool
    {
        return true;
    }

    public function rules():array
    {
        return [
            'excelFiles' => 'required|array',
            'excelFiles.*' => 'file|mimes:xlsx,xls'
        ];
    }

    public function messages():array
    {
        return [
            'file.required' => 'Vui lòng chọn ít nhất một file.',
            'file.array' => 'File không hợp lệ.',
            'file.*.mimes' => 'File phải có định dạng .xls hoặc .xlsx.',
            'file.*.max' => 'Dung lượng file tối đa là 2MB.'
        ];
    }
}
