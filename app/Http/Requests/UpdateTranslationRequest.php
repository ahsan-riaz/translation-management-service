<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTranslationRequest extends FormRequest
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
        $id = $this->route('id');

        return [
            'key' => 'sometimes|string|unique:translations,key,' . $id,
            'group' => 'nullable|string',
            'values' => 'array',
            'values.*.locale' => 'required|string',
            'values.*.value' => 'required|string',
            'tags' => 'array'
        ];
    }
}
