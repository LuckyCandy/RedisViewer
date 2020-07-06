<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RedisClientRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'bail|required',
            'address' => 'bail|required',
            'port' => 'bail|required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '需要给服务起个别名哦',
            'password.required' => '连接不要密码？太奔放了点吧',
            'address.required' => '不给地址，会迷路的哦',
            'port.required' => '虽然有默认值，但还是填上的好:(6379?)',
        ];
    }
}
