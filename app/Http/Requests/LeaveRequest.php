<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeaveRequest extends FormRequest
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
            'fromdate'  =>  'required|date_format:Y-m-d',
            'todate'    =>  'required|date_format:Y-m-d',
            'leavecode'   =>  'required',
            'reason'    =>  'required',
            // 'pdf'       =>  'required',
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
            'fromdate.required' => 'From date field is required.',
            'todate.required'   => 'To date field is required.',
            'leavecode.required'  => 'Please select leave type.',
            'reason.required'   =>  'Reason field is required.',
        ];
    }
}
