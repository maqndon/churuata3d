<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        # update HTTP method used
        $method = $this->method();

        if ($method == 'PUT') {
            return [
                'created_by' => 'required|numeric',
                'licence_id' => 'required|numeric',
                'title' => [
                    'required',
                    'max:255',
                    Rule::unique('products', 'title')->ignore($this->input('title'), 'id'),
                ],
                'slug' => [
                    'required',
                    'max:255',
                    Rule::unique('products', 'slug')->ignore($this->input('slug'), 'slug'),
                ],
                'sku' => [
                    'required',
                    'max:255',
                    Rule::unique('products', 'sku')->ignore($this->input('sku'), 'sku'),
                ],
                'excerpt' => 'required|string',
                'body' => 'required|string',
                'stock' => 'numeric',
                'price' => 'exclude_unless:is_free,false|required|numeric',
                'sale_price' => 'exclude_unless:is_free,false|required|numeric',
                'status' => 'required',
                'is_featured' => 'required|boolean',
                'is_downloadable' => 'required|boolean',
                'is_free' => 'required|boolean',
                'is_printable' => 'required|boolean',
                'is_parametric' => 'required|boolean',
                'related_parametric' => 'string',
                'downloads' => 'numeric',
            ];
        } else {
            return [
                'created_by' => 'sometimes|required|numeric',
                'licence_id' => 'sometimes|required|numeric',
                'title' => [
                    'sometimes',
                    'required',
                    'max:255',
                    Rule::unique('products', 'title')->ignore($this->input('title'), 'title'),
                ],
                'slug' => [
                    'sometimes',
                    'required',
                    'max:255',
                    Rule::unique('products', 'slug')->ignore($this->input('slug'), 'slug'),
                ],
                'sku' => [
                    'sometimes',
                    'required',
                    'max:255',
                    Rule::unique('products', 'sku')->ignore($this->input('sku'), 'sku'),
                ],
                'excerpt' => 'sometimes|required|string',
                'body' => 'sometimes|required|string',
                'stock' => 'numeric',
                'price' => 'exclude_unless:is_free,false|required|numeric',
                'sale_price' => 'exclude_unless:is_free,false|required|numeric',
                'status' => 'sometimes|required',
                'is_featured' => 'sometimes|required|boolean',
                'is_downloadable' => 'sometimes|required|boolean',
                'is_free' => 'sometimes|required|boolean',
                'is_printable' => 'sometimes|required|boolean',
                'is_parametric' => 'sometimes|required|boolean',
                'related_parametric' => 'string',
                'downloads' => 'numeric',
            ];
        }
    }
}
