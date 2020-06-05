<?php

namespace App\Http\Requests;

use App\Exceptions\LoginFailedException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $user = auth()->user();
        return [
            'name' => 'bail|required|unique:users,name,' . $user->id,
            'password' => 'bail|alpha_dash|password:api|different:new_password',
            'new_password' => 'bail|alpha_dash|between:6,30|required_with:password'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '必须填写昵称',
            'name.unique' => '昵称已存在',
            'password.password' => '原密码错误',
            'password.different' => '新旧密码不能一样',
            'password.alpha_dash' => '密码只能包含字母、数字、中划线或下划线',
            'new_password.required_with' => '请输入新密码',
            'new_password.between' => '密码长度需在6-30个字符之间',
            'new_password.alpha_dash' => '新密码只能包含字母、数字、中划线或下划线',
        ];
    }
}
