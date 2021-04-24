<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OvertimeRequest extends FormRequest
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
            'clientname'    =>  'required',
            'workdone'      =>  'required',
            'timestart'     =>  'required',
            'timefinish'    =>  'required',
            'hours'         =>  'required|numeric|min:0|not_in:0',
            'shift_code'    =>  'required'
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
            'clientname.required'   =>  'Client name field is required.',
            'workdone.required'     =>  'Work done field is required.',
            'timestart.required'    =>  'Time start field is required.',
            'timefinish.required'   =>  'Time finish field is required.',
            // 'hours.required'        =>  'Reason field is required.',
            // 'shift_code.required'   =>  'Reason field is required.',
        ];
    }
}
