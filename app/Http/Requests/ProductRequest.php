<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => 'required|string',
            'author' => 'required|numeric',
            'enItemId' => 'nullable|numeric',
            'category' => 'required|numeric',
            'releaseDate' => 'required|date',
            'thumbnail' => 'nullable|numeric',
            'developer' => 'nullable|numeric'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
