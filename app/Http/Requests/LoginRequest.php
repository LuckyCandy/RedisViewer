<?php

namespace App\Http\Requests;

use App\Exceptions\LoginFailedException;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        /* Indicate default guard */
        auth()->shouldUse('api');

        return auth()->attempt($this->only('email', 'password'));
    }

    /**
     * @throws LoginFailedException
     */
    protected function failedAuthorization()
    {
        throw new LoginFailedException;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'bail|required|email',
            'password' => 'bail|required'
        ];
    }
}
