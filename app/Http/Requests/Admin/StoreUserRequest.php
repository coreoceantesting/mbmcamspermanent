<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
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
            'department_id' => 'required|exists:departments,id',
            // 'sub_department_id' => 'required|exists:departments,id',
            'emp_code' => 'nullable|max:15|unique:app_users,emp_code|regex:/^[A-Z0-9]+$/',
            // 'dob' => 'required',
            'gender' => ['required', Rule::in(['m', 'f', 'o'])],
            'role' => 'required',
            'name' => 'required',
            // 'ward_id' => 'required',
            'email' => 'required|unique:app_users,email',
            'mobile' => 'nullable|unique:app_users,mobile|digits:10',
            'password' => 'required|min:8',
            'confirm_password' => 'required|same:password',
        ];
    }
}
