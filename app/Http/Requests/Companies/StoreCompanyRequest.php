<?php

namespace App\Http\Requests\Companies;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompanyRequest extends FormRequest
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
            'name'=> 'required|unique:companies,name',
            'email'=> 'nullable|email|unique:companies,email',
            'logo'=> 'nullable|image|dimensions:min_width=100,min_height=100',
            'website'=> 'nullable|url|unique:companies,website',
        ];
    }
}
