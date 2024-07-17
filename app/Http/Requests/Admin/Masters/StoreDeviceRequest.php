<?php

namespace App\Http\Requests\Admin\Masters;

use Illuminate\Foundation\Http\FormRequest;

class StoreDeviceRequest extends FormRequest
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
            'ward_id' => 'required',
            'DeviceFName' => 'required',
            'DevicesName' => 'required',
            'SerialNumber' => 'required|max:30',
            'IpAddress' => 'required|ipv4',
            'DeviceLocation' => 'required',
        ];
    }
}
