<?php

namespace App\Http\Requests\Tags;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTagRequest extends FormRequest
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
        $tagId = $this->route('tag');

        if ($method == 'PUT') {
            return [
                'name' => [
                    'required',
                    'string',
                    Rule::unique('tags', 'name')->ignore($tagId, 'id')
                ],
                'slug' => [
                    'required',
                    'max:255',
                    Rule::unique('tags', 'slug')->ignore($tagId, 'id')
                ]
            ];
        } else {
            return [
                'name' => [
                    'sometimes',
                    'required',
                    'string',
                    Rule::unique('tags', 'name')->ignore($tagId, 'id')
                ],
                'slug' => [
                    'sometimes',
                    'required',
                    'max:255',
                    Rule::unique('tags', 'slug')->ignore($tagId, 'id')
                ]
            ];
        }
    }
}
