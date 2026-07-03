<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePositionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $mergeData = [];
        if ($this->has('min_salary')) {
            $mergeData['min_salary'] = str_replace('.', '', $this->min_salary);
        }
        if ($this->has('max_salary')) {
            $mergeData['max_salary'] = str_replace('.', '', $this->max_salary);
        }
        if (!empty($mergeData)) {
            $this->merge($mergeData);
        }
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'min_salary' => 'required|numeric|min:0',
            'max_salary' => 'required|numeric|min:0|gte:min_salary',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Nama jabatan wajib diisi',
            'min_salary.required' => 'Gaji minimum wajib diisi',
            'max_salary.required' => 'Gaji maksimum wajib diisi',
            'max_salary.gte' => 'Gaji maksimum harus lebih besar atau sama dengan gaji minimum',
        ];
    }
}
