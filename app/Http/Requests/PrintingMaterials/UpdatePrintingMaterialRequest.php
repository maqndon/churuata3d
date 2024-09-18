<?php

namespace App\Http\Requests\PrintingMaterials;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePrintingMaterialRequest extends FormRequest
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
        $printingMaterialId = $this->route('printing_material');
      
        if ($method == 'PUT') {
            return [
                'name' => [
                    'required',
                    'string',
                    Rule::unique('printing_materials')->where(function ($query) use ($printingMaterialId) {
                        $query->where('name', $this->input('name'))
                            ->where('nozzle_size', $this->input('nozzle_size'))
                            ->where('id', '!=', $printingMaterialId);
                    }),
                ],
                'nozzle_size' => 'required|decimal:1',
                'min_hot_bed_temp' => 'required|numeric',
                'max_hot_bed_temp' => 'required|numeric',
            ];
        } else {
            return [
                'name' => [
                    'sometimes',
                    'required',
                    'string',
                    Rule::unique('printing_materials')->where(function ($query) use ($printingMaterialId) {
                        $query->where('name', $this->input('name'))
                            ->where('nozzle_size', $this->input('nozzle_size'))
                            ->where('id', '!=', $printingMaterialId);
                    }),
                ],
                'nozzle_size' => 'sometimes|required|decimal:1',
                'min_hot_bed_temp' => 'sometimes|required|numeric',
                'max_hot_bed_temp' => 'sometimes|required|numeric',
            ];
        }
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'This printing material with this nozzle size has already been taken.',
        ];
    }
}
