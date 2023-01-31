<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:191',
            'start_date' => 'required|string|max:191',
            'end_date' => 'required|string|max:191',
            'file_id' => 'required|integer',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
