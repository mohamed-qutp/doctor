<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;


class AssignRequest extends FormRequest
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
            'name' => 'required|string|max:100',
            'password' => ['required', Password::defaults()],
            'phone' => 'required|unique:users,phone|max:20|min:5',
            'code'=> 'required|string|exists:countries,dial_code',
            'department_id'=> 'required|exists:departments,id',
            'title_id'=> 'required|exists:titles,id',
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
