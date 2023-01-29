<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class SignupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    // ! rule에 대해서 정확하게 파악을 해 두어야 할거 같다
    public function rules()
    {
        return [
            'name' => 'required|string|max:55',
            'email' => 'required|string|email|max:255',
            'password' => [
                'required',
                'string',
                'min:8',
                'max:255',
                'required_with:confirmPassword',
                'same:confirmPassword',
            ],
            'confirmPassword' => [
                'string',
                'min:8',
                'max:255',
            ],
        ];
    }
    /**
     * Get the error messages for the defined validation rules.*
     * @return array
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'ok' => false,
            'errors' => $validator->errors(),
            'status' => 422,
        ], 422));
    }
}