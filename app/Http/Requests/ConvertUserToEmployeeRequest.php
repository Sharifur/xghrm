<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConvertUserToEmployeeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'userId' => 'required|integer',
            'address' => 'required_if:employee_type,new',
            'catId' => 'required_if:employee_type,new',
            'dateOfBirth' => 'required_if:employee_type,new',
            'joinDate' => 'required_if:employee_type,new',
            'emergencyNumber' => 'required_if:employee_type,new',
            'employee_type' => 'required|string',
            'existing_employee_id' => 'required_if:employee_type,existing',
            'imageId' => 'nullable|integer',
            'status' => 'required_if:employee_type,new',
            'personalInfo' => 'nullable|string',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
