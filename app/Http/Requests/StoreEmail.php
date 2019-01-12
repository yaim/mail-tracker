<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmail extends FormRequest
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
            'from_email_address' => 'email|required|max:255',
            'to_email_address'   => 'email|required|max:255',
            'subject'            => 'required|max:255',
            'content'            => 'required',
        ];
    }
}
