<?php

namespace App\Http\Requests\Admin\Masters;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLeaveRequestHierarchiesRequest extends FormRequest
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
        $rules = [
            'clas_id' => 'required|exists:clas,id',
            'requester_designation_id' => 'required|exists:designations,id',
            'requester_department_id' => 'required|exists:departments,id',
        ];

        // Loop approver fields 1 to 4
       for ($i = 1; $i <= 4; $i++) {
            if ($i == 1) {
                $rules["{$i}_approver_designation_id"] = 'required|exists:designations,id';
                $rules["{$i}_approver_department_id"] = 'required|exists:departments,id';
            } else {
                $rules["{$i}_approver_designation_id"] = 'nullable|exists:designations,id';
                $rules["{$i}_approver_department_id"] = 'nullable|exists:departments,id';
            }
        }


        return $rules;
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $hasAtLeastOneApprover = false;

            for ($i = 1; $i <= 4; $i++) {
                $designation = $this->input("{$i}_approver_designation_id");
                $department = $this->input("{$i}_approver_department_id");

                // If either is filled, both are required
                if ($designation || $department) {
                    $hasAtLeastOneApprover = true;

                    if (!$designation) {
                        $validator->errors()->add("{$i}_approver_designation_id", "Designation is required when department is filled for Approver {$i}.");
                    }

                    if (!$department) {
                        $validator->errors()->add("{$i}_approver_department_id", "Department is required when designation is filled for Approver {$i}.");
                    }
                }
            }

            if (!$hasAtLeastOneApprover) {
                $validator->errors()->add('approver_required', 'At least one approver (designation and department) must be filled.');
            }
        });
    }


    public function messages(): array
{
    $messages = [
        'clas_id.required' => 'Class is required.',
        'clas_id.exists' => 'The selected class does not exist.',

        'requester_designation_id.required' => 'Requester designation is required.',
        'requester_designation_id.exists' => 'The selected requester designation does not exist.',

        'requester_department_id.required' => 'Requester department is required.',
        'requester_department_id.exists' => 'The selected requester department does not exist.',
    ];

    // Custom messages for dynamic approver fields
    for ($i = 1; $i <= 4; $i++) {
        $messages["{$i}_approver_designation_id.required"] = "Approver {$i} designation is required.";
        $messages["{$i}_approver_designation_id.exists"] = "The selected designation for Approver {$i} does not exist.";

        $messages["{$i}_approver_department_id.required"] = "Approver {$i} department is required.";
        $messages["{$i}_approver_department_id.exists"] = "The selected department for Approver {$i} does not exist.";
    }

    // Message for custom validation in withValidator
    $messages['approver_required'] = 'At least one approver (designation and department) must be provided.';

    return $messages;
}

}
