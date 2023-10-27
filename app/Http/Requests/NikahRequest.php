<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NikahRequest extends FormRequest
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
        $rules =  [
            'nikah_type_id' => 'required|integer',
            'user_applied_nikah_id' => 'required|integer',
            'partner_id' => 'required|integer',
            'nikah_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'services.*' => 'required',
//            'witness_email.*' => 'required',
//            'services' => 'in:print,video,certificate'
        ];

       /* foreach($this->request->get('services') as $key => $value){
            $rules['services.'.$key.'.0'] = 'required';
            $rules['services.'.$key.'.1'] = 'required_if_not:services.*.0|print';
            $rules['services.'.$key.'.2'] = 'required_if_not:services.*.1|video';
            $rules['services.'.$key.'.3'] = 'required_if_not:services.*.2|certificate';
        }*/

        return $rules;
    }

    public function messages()
    {
        return [
            'nikah_type_id.required' => trans('validation.required'),
            'nikah_type_id.integer' => trans('validation.integer'),
            'user_applied_nikah_id.required' => trans('validation.required'),
            'user_applied_nikah_id.integer' => trans('validation.integer'),
            'nikah_date.required' => trans('validation.required'),
            'start_time.required' => trans('validation.required'),
            'end_time.required' => trans('validation.required'),
            'nikah_date.date' => trans('validation.date'),
        ];
    }
}
