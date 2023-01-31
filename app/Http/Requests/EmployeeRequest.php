<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'personalInfo' => 'nullable|string',
            'paymentInfo' => 'nullable|string',
            'att_id' => 'nullable|string',
            'emergencyNumber' =>  'required|numeric',
            'address' => 'required|string',
            'mobile' => 'required|numeric',
            'salary' => 'required|numeric',
            'name' => 'required|string',
            'email' => 'required|email',
            'catId' => 'required|numeric',
            'imageId' => 'nullable|numeric',
            'joinDate' =>  'required',
            'dateOfBirth' =>  'required',
            'status' =>  'required|numeric',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
