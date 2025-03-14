<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'employeeID' => 'required|unique:employees,employeeID',
            'username' => 'required|unique:users,username',
            'email' => 'required|unique:users,email|email',
            'full_name' => 'required',
            'phonenumber' => 'required',
            'personal_email' => 'email',
            'aadhaar' => 'integer',
            'date_of_birth' => 'date',
            'join_date' => 'date',
            'profile_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }
}
