<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;

class RoleUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if($this->user()->can('تعديل الوظائف')){return true ;}
        return false;
    }

    protected function failedAuthorization()
    {
        throw new AuthorizationException('Sorry, Un Authorized, This action for admins Only');

    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'permissions' => ['required'],
            'permissions.*'=>['exists:permissions,name'],
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