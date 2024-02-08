<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class CategoryStoreRequest extends FormRequest
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
            'name_en'=> 'required|string',
            'name_ar'=> 'required|string',
            'description_en'=> 'required|string',
            'description_ar'=> 'required|string',
            'img'=>'required|image|mimes:jpg,png,gif',
        ];
    }
    protected function failedValidation(Validator $validator)
    {      // return response()->json(['message' => collect($validator->errors())->flatten(1), 'code' => 422], 422);
        throw new ValidationException($validator, response()->json([
            'status' => 'false',
            'message' => 'Validation failed',
            'errors' =>   collect($validator->errors())->flatten(1)
        ], 422));
    }
}
