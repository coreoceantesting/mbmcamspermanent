<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ImportShiftRosterRequest extends FormRequest
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
            'file'=> 'required|mimes:xls,xlsx,csv',
            'from_date'=> 'required|date',
            'to_date'=> 'required|date|after:from_date',
        ];
    }
}
