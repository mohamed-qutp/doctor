<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class UpdateUserRequest extends FormRequest
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
            'name' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:100',
            'password' => ['nullable', Password::defaults()],
            'phone' => 'nullable|max:14|min:5',
            'country_id'=>'nullable|exists:countries,id',
            'city_id'=>'nullable|exists:cities,id',
            'birth_date' =>'nullable|date',
            'img'=>'nullable|image|mimes:jpg,png',
            'gender' => [
                'nullable',
                Rule::in(['male', 'female']),
            ],
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new ValidationException($validator, response()->json([
            'code' =>422,
            'status' => 'false',
            'message' =>   collect($validator->errors())->flatten(1)
        ], 422));
    }
}
