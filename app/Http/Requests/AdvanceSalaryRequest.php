<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdvanceSalaryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'employee_id' => 'required|integer',
            'month' => 'required',
            'amount' => 'required'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
