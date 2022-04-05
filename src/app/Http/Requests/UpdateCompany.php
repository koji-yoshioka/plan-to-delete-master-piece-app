<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompany extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:255',
            'email' => 'required|email:rfc,dns',
            'zipCode' => 'string|nullable|regex:/^\d{7}$/',
            'images' => 'array',
            'images.*.*' => 'file|mimes:jpg,jpeg,png',
        ];
    }
}
