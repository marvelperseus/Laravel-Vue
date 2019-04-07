<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveAppointmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'patient_name' => ['required', 'string', 'max:191'],
            'patient_tel' => ['required', 'string', 'max:191'],
            'patient_email' => ['required', 'email', 'max:191'],
            'patient_birthday' => ['required', 'date', 'before:today'],
        ];
    }
}
