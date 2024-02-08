<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;


class UpdateUsersRequest extends FormRequest
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
            'password' => ['nullable', Password::defaults()],
            'phone' => 'nullable|max:20|min:5',
            'code'=> 'nullable|string|exists:countries,dial_code',
            'department_id'=> 'nullable|exists:departments,id',
            'title_id'=> 'nullable|exists:titles,id',
            'user_type'=>'exists:departments,name_en'
            // 'user_type'=> [
            //     'required',
            //     Rule::in(['patient', 'admin', 'doctor' , 'nurse']),
            // ],
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
