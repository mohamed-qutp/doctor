<?php

namespace App\Http\Requests;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Http\FormRequest;

class BannerUpdateRequest extends FormRequest
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
            'img'=>'nullable|image|mimes:jpg,png',
        ];
    }
    protected function failedValidation(Validator $validator)
    {

        // return response()->json(['message' => collect($validator->errors())->flatten(1), 'code' => 422], 422);
        throw new ValidationException($validator, response()->json([
            'status' => 'false',
            'message' => 'Validation failed',
            'errors' =>   collect($validator->errors())->flatten(1)
        ], 422));
    }
}
