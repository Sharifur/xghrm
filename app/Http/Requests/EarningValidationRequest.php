<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EarningValidationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'en_username' => 'required|max:191',
            'title' => 'required|max:191',
            'from' => 'required|numeric',
            'month' => 'required|date',
            'percentage' => 'required|numeric',
            'statement' => 'nullable|integer',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
