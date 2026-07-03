<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSalaryStructureRequest extends FormRequest
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
        if ($this->has('basic_salary')) {
            $this->merge([
                'basic_salary' => str_replace('.', '', $this->basic_salary),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'employee_id' => 'required|exists:employees,id',
            'basic_salary' => 'required|numeric|min:0',
            'effective_date' => [
                'required',
                'date',
                \Illuminate\Validation\Rule::unique('salary_structures')
                    ->ignore($this->salary_structure->id)
                    ->where(function ($query) {
                        return $query->where('employee_id', $this->employee_id)
                                     ->where('effective_date', $this->effective_date);
                    }),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'employee_id.required' => 'Karyawan wajib dipilih',
            'basic_salary.required' => 'Gaji pokok wajib diisi',
            'effective_date.required' => 'Tanggal efektif wajib diisi',
            'effective_date.unique' => 'Karyawan ini sudah memiliki struktur gaji pada tanggal tersebut',
        ];
    }
}
