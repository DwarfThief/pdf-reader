<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FileFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'file' => 'required|mimes:pdf',
            'choosenType' => 'required|in:csv,xlsx',
        ];
    }

    /**
     * Return error messages
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'file.required' => 'Campo obrigatório',
            'file.mimes' => 'Formato incorreto',
            'choosenType.required' => 'Campo obrigatório',
            'choosenType.in' => 'Escolha um tipo para exportar',
        ];
    }
}
