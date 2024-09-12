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

        if ($method == 'PUT') {
            return [
                'name' => [
                    'required',
                    'string',
                    Rule::unique('tags', 'name')->ignore($this->input('name'), 'name')
                ],
                'slug' => [
                    'required',
                    'max:255',
                    Rule::unique('tags', 'slug')->ignore($this->input('slug'), 'slug'),
                ]
            ];
        } else {
            return [
                'name' => [
                    'sometimes',
                    'required',
                    'string',
                    Rule::unique('tags', 'name')->ignore($this->input('name'), 'name')
                ],
                'slug' => [
                    'sometimes',
                    'required',
                    'max:255',
                    Rule::unique('tags', 'slug')->ignore($this->input('slug'), 'slug'),
                ]
            ];
        }
    }
}
