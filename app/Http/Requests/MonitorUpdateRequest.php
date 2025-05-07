<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MonitorUpdateRequest extends FormRequest
{
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
            'url' => 'url|required',
            'look_for_string' => 'string|nullable',
            'uptime_check_enabled' => 'sometimes|boolean',
            'certificate_check_enabled' => 'sometimes|boolean',
        ];
    }
}
