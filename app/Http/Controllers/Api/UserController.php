<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class UserController extends Controller
{
    public function user()
    {
        return response()->jsr(200, auth()->user());
    }

    public function update(UserUpdateRequest $request)
    {
        $user = auth()->user();
        $params = $request->only(['name', 'password', 'new_password']);
        $user->name = $request->get('name');
        if (Arr::get($params, 'password')) {
            $user->password = bcrypt($params['new_password']);
        }

        return $user->save() ? response()->jsr() : response()->jsr(500, [], '修改信息失败');
    }
}
