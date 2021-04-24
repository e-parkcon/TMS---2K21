<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Employee extends FormRequest
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
            //
            'empl_date' =>  'required',
            'birth_date'    =>  'required',
            'shifts'     =>  'required',
            'entity01'  =>  'required',
            'entity02'  =>  'required',
            'entity03'  =>  'required',
            'deptcode'  =>  'required',
            'password'  => 'required|min:6',
            // 'password' => 'required|confirmed|min:6',
            'email'     =>  'required|email',
            'phoneNum' =>  'required|numeric|min:11',
            'lname'     =>  'required',
            'mname'     =>  'required',
            'fname'     =>  'required',
            'user_level'    =>  'required',
            'empno'     =>  'required|numeric|unique:emp_master',
        ];
    }
    
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'empno.required' => 'ID # field is required.',
            'empno.unique'   => 'ID # is already taken.',
            'user_level.required' => 'User level field is required.',
            'fname.required' => 'First name field is required.',
            'mname.required' => 'Middle name field is required.',
            'lname.required' => 'Last name field is required.',
            'phoneNum.required' => 'Phone # field is required.',
            'email.required' => 'Email field is required.',
            'password.required' =>  'Password field is required',
            'entity01.required' =>  'Company field is required.',
            'entity02.required' =>  'District field is required.',
            'entity03.required' =>  'Branch field is required.',
            'deptcode.required' =>  'Department field is required.',
            'shifts.required'    =>  'Shift field is required.',
            'birth_date.required'   =>  'Birth date field is required.',
            'empl_date.required'    =>  'Hired field is required.',
        ];
    }
}
