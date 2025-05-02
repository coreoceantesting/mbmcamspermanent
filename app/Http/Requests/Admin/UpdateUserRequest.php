<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'department_id' => 'required|exists:departments,id',
            // 'sub_department_id' => 'required|exists:departments,id',
            'emp_code' => 'nullable',
            // 'dob' => 'required',
            'gender' => ['required', Rule::in(['m', 'f', 'o'])],
            'role' => 'required',
            'name' => 'required',
            // 'ward_id' => 'required',
            'email' => 'required',
            'mobile' => 'nullable|digits:10',
            'clas_id' => 'required',
            'designation_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'clas_id.required' => 'The class field is required.',
            'designation_id.required' => 'The designation field is required.',
            'department_id'=>'The department field is required.',
        ];
    }
}
