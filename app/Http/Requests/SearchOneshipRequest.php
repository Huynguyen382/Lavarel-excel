<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchOneshipRequest extends FormRequest
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
            'search' =>'nullable|string|max:255',
        ];
    }
    public function messages()
    {
        return [
            'search.string' => 'Từ khóa tìm kiếm phải là chuỗi ký tự.',
            'search.max' => 'Từ khóa tìm kiếm không được dài quá 255 ký tự.',
        ];
    }
}
