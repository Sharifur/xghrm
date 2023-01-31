<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeCategoryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => 'required|max:191',
            'status' => 'required|numeric',
            'image' => 'nullable|string|max:191',
            'icon' => 'nullable|string|max:191',
            'id' => 'nullable|max:191',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
