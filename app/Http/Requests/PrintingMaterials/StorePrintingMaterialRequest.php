<?php

namespace App\Http\Requests\PrintingMaterials;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StorePrintingMaterialRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                Rule::unique('printing_materials')->where(function ($query) {
                    $query->where('name', $this->input('name'))
                        ->where('nozzle_size', $this->input('nozzle_size'));
                }),
            ],
            'nozzle_size' => 'required|decimal:1',
            'min_hot_bed_temp' => 'required|numeric',
            'max_hot_bed_temp' => 'required|numeric',
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'This printing material with this nozzle size has already been taken.',
        ];
    }
}
