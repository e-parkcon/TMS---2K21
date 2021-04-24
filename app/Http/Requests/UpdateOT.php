<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOT extends FormRequest
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
            'clientname_edit'    =>  'required',
            'workdone_edit'      =>  'required',
            'timestart_edit'     =>  'required',
            'timefinish_edit'    =>  'required',
            'hours_edit'         =>  'required|numeric|min:0|not_in:0',
            'shift_code_edit'    =>  'required'
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
            'clientname_edit.required'   =>  'Client name field is required.',
            'workdone_edit.required'     =>  'Work done field is required.',
            'timestart_edit.required'    =>  'Time start field is required.',
            'timefinish_edit.required'   =>  'Time finish field is required.',
            // 'hours.required'        =>  'Reason field is required.',
            // 'shift_code.required'   =>  'Reason field is required.',
        ];
    }
}
