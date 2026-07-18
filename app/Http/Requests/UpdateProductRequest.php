<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string'],
            'category_id' => ['sometimes', 'exists:categories,id'],
            'description' => ['sometimes', 'nullable', 'string'],
            'price' => ['sometimes', 'numeric'],
            'stock' => ['sometimes', 'integer', 'min:0'],
            'status' => ['sometimes', 'in:active,inactive'],

            'option_values' => ['sometimes', 'array'],
            'option_values.*' => ['exists:option_values,id'],
        ];
    }
}
