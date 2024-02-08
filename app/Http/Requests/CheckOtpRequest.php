<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class CheckOtpRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'phone' => 'required|exists:password_reset_tokens,phone',
            'code'=> 'required|string|exists:password_reset_tokens,code',
            'otp' => 'required|exists:password_reset_tokens,otp'
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        // return response()->json(['message' => collect($validator->errors())->flatten(1), 'code' => 422], 422);
        throw new ValidationException($validator, response()->json([
            'code' =>422,
            'status' => 'false',
            'message' =>   collect($validator->errors())->flatten(1)
        ], 422));
    }
}
