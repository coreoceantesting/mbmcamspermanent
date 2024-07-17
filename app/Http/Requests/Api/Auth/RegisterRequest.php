<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'ward_id'=> 'required',
            'department_id'=> 'required',
            'clas_id'=> 'required',
            'designation_id'=> 'required',
            'emp_code'=> 'required|max:20',
            'name'=> 'required|max:150',
            'email'=> 'required|email',
            'mobile'=> 'required|digits:10',
            'dob'=> 'nullable',
            'gender'=> 'required|in:m,f,o',
            'password'=> 'required',
            'confirm_password'=> 'required|same:password',
        ];
    }
}
