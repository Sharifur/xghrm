<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SalarySlipRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'employee_id' => 'required|integer',
            'month' => 'required',
            'extraEarningFields' => 'nullable',
            'extraDeductionFields' => 'nullable',
            'salary' => 'nullable'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
