<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function user()
    {
        return response()->jsr(200, auth()->user());
    }
}
