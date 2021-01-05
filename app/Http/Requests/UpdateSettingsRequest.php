<?php

namespace App\Http\Requests;

use Anik\Form\FormRequest;

class UpdateSettingsRequest extends FormRequest
{
    protected function authorize () {
        return true;
    }

    protected function rules () {
        return [
            'current_password' => [ 'required_with:new_password' ],
            'new_password'     => [ 'required_with:current_password', 'min:5', 'confirmed' ],
            'name'             => [ 'min:3', 'max:50' ],
            'email'            => [ 'max:80', 'email' ],
        ];
    }
}
