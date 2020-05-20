<?php
/**
 * Created by PhpStorm.
 * User: damonli
 * Date: 2020-05-20
 * Time: 11:17
 */

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;

class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        return ['1' => 2];
    }
}