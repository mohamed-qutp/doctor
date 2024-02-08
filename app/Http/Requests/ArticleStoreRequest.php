<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class ArticleStoreRequest extends FormRequest
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
            "title_en"=> 'nullable|string',
            'title_ar'=> 'nullable|string',
            'description_en'=> 'nullable|string',
            'description_ar'=> 'nullable|string',
            'img'=>'nullable|image|mimes:jpg,png,gif,mp4,mp4v,',
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
