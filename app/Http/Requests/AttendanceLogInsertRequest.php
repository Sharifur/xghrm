<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceLogInsertRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'importType' => 'required|string',
            'attendance_report_id' => 'required|integer',
            'csv_column' => 'required_if:importType,==,individual',
            'column_value' => 'required_if:importType,==,individual',
            'attendance_column_value' => 'required',
            'attendance_type_column_value' => 'required',
            'employee_id' => 'required_if:importType,==,individual',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
