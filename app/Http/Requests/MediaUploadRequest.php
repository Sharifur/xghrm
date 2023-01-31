<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MediaUploadRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'file' => 'nullable|mimes:jpg,jpeg,png,gif,pdf,doc,docx,txt,svg,zip,csv,xlsx,xlsm,xlsb,xltx,pptx,pptm,ppt|max:2000000'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
