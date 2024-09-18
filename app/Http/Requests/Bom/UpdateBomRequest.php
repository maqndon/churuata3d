<?php

namespace App\Http\Requests\Bom;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBomRequest extends FormRequest
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
        $bomId = $this->route('bom')->id;

        if ($method == 'PUT') {
            return [
                'qty' => 'required|numeric',
                'item' => [
                    'required',
                    'string',
                    Rule::unique('boms', 'item')->ignore($bomId, 'id')
                ],
            ];
        } else {
            return [
                'qty' => 'sometimes|required|numeric',
                'item' => [
                    'sometimes',
                    'required',
                    'string',
                    Rule::unique('boms', 'item')->ignore($bomId, 'id')
                ],
            ];
        }
    }
}
