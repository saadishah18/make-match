<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
//        dd($this->user->id);
        return [
            'profile_image' => 'nullable|image|mimes:in:jpeg,png,jpg,gif,svg|max:10240',
            'selfie' => 'nullable|image|mimes:in:jpeg,png,jpg,gif,svg|max:10240',
            'phone' => 'nullable|min:11|max:14|'.Rule::unique('users')->ignore($this->user()->id),
            'email' => 'nullable|email|'.Rule::unique('users')->ignore($this->user()->id),
            'id_card_number' => 'nullable|min:13|max:17|'.Rule::unique('users')->ignore($this->user()->id),
            'id_card_front' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
            'id_card_back' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
            'gender' => 'nullable|string|in:male,female'
        ];
    }

    public function messages()
    {
        return [
            'profile_image.nullable' => trans('validation.image'),
            'profile_image.mimes' => trans('validation.mimes'),
            'profile_image.max' => trans('validation.max'),
            'selfie.nullable' => trans('validation.image'),
            'selfie.mimes' => trans('validation.mimes'),
            'selfie.max' => trans('validation.max'),
            'id_card_front.nullable' => trans('validation.image'),
            'id_card_front.mimes' => trans('validation.mimes'),
            'id_card_front.max' => trans('validation.max'),
            'id_card_back.nullable' => trans('validation.image'),
            'id_card_back.mimes' => trans('validation.mimes'),
            'id_card_back.max' => trans('validation.max'),
            'phone.nullable' => trans('validation.unique')
        ];
    }
}
