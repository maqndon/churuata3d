<?php

namespace App\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
        return [
            'created_by' => 'required|numeric',
            'licence_id' => 'required|numeric',
            'title' => 'required|unique:products|max:255',
            'slug' => 'required|alpha_dash|unique:products|max:255',
            'sku' => 'required|unique:products|max:255',
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
            'created_at' => 'required|date'
        ];
    }
}
