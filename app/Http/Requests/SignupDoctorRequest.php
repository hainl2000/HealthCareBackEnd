<?php

namespace App\Http\Requests;

class SignupDoctorRequest extends ApiRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'email' => 'required|unique:doctors|email',
            'gender' => 'required',
            'type' => 'required|in:0,1',
            'specialization_id' => 'required',
            'shortIntro' => 'required',
            'fullIntro' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'email.unique' => 'Email đã được sử dụng'
        ];
    }
}
