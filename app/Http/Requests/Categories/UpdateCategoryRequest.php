<?php

namespace App\Http\Requests\Categories;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
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
        # update HTTP method used
        $method = $this->method();
        $categoryId = $this->route('category')->id;

        if ($method == 'PUT') {
            return [
                'name' => [
                    'required',
                    'string',
                    Rule::unique('categories', 'name')->ignore($categoryId, 'id')
                ],
                'slug' => [
                    'required',
                    'max:255',
                    Rule::unique('categories', 'slug')->ignore($categoryId, 'id')
                ]
            ];
        } else {
            return [
                'name' => [
                    'sometimes',
                    'required',
                    'string',
                    Rule::unique('categories', 'name')->ignore($categoryId, 'id')
                ],
                'slug' => [
                    'sometimes',
                    'required',
                    'max:255',
                    Rule::unique('categories', 'slug')->ignore($categoryId, 'id')
                ]
            ];
        }
    }
}
