<?php

namespace App\Http\Requests;

use Anik\Form\FormRequest;

class CreateAccountRequest extends FormRequest
{
    protected function authorize () {
        return true;
    }

    protected function rules () {
        return [
            'name'     => [ 'required', 'min:3', 'max:50' ],
            'username' => [ 'bail', 'required', 'max:30', 'username', 'unique:users,username', ],
            'email'    => [ 'bail', 'required', 'max:80', 'email', 'unique:users,email', ],
            'password' => [ 'required', 'min:5', 'confirmed' ],
        ];
    }
}
