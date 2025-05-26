<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use phpDocumentor\Reflection\Types\Nullable;

class UpdateEmployeeRequest extends FormRequest
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
            'emp_code' => 'required',
            'name' => 'required',
            'email' => 'nullable|sometimes|email',
            'mobile' => 'nullable|sometimes|digits:10',
            'aadhaar_no' => 'nullable|sometimes|digits:12',
            'dob' => 'nullable|sometimes|date',
            'doj' => 'nullable|sometimes|date',
            'gender' => ['required', Rule::in(['m', 'f', 'o'])],
            'permanent_address' => 'nullable',
            'present_address' => 'nullable',
            'employee_type' => 'nullable',
            // 'contractor_id'=> 'required_if:employee_type,0',
            'contractor_id' => 'nullable',
            'is_rotational' => 'required',
            'work_duration' => 'nullable',
            'sa_duration' => 'nullable',

            'device_id' => 'nullable',
            'department_id' => 'required',
            'sub_department_id' => 'nullable',
            'is_ot' => ['required', Rule::in(['y', 'n'])],
            'is_divyang' => ['required', Rule::in(['y', 'n'])],
            'shift_id' => 'required_if:is_rotational,0',
            'in_time' => 'nullable',
            'ward_id' => 'required',
            'clas_id' => 'nullable',
            'designation_id' => 'nullable',
            'leave_durations'=>'nullable',

            'issue_order_date' => 'required|date',
            'is_benifit' => 'required|in:0,1',
            'benefit_document' => $this->hasFile('benefit_document') || !$this->benefit_document_existing ? 'required|file|mimes:pdf,jpg,jpeg'  : 'nullable|file|mimes:pdf,jpg,jpeg',
            'fixation_date' => 'required|date',
            'fixation_document' => !$this->fixation_document_existing ? 'required|file|mimes:pdf,jpg,jpeg': 'nullable|file|mimes:pdf,jpg,jpeg',
        ];
    }


    public function messages()
    {
        return [
            'benefit_document.required_if' => 'The upload document field is required.',
            'fixation_document.required'=>'The upload document field is required.',
            'shift_id.required'=>'The shift field is required.',
            'device_id.required' => 'The device field is required.',
            'department_id.required' => 'The department field is required.',
            'ward_id.required' => 'The ward field is required.',
            'clas_id.required' => 'The clas field is required.',
        ];
    }
}
