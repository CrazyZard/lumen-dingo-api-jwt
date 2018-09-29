<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class  AuthController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
    }

    //登陆
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (! $token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => '账号密码错误'], 401);
        }
        return $this->respondWithToken($token);
    }
    //账号退出
    public function logout()
    {
        JWTAuth::parseToken()->invalidate();

        return response()->json(['message' => '账号退出成功']);
    }

    //重新刷新token
    public function refresh()
    {
        return $this->respondWithToken(JWTAuth::parseToken()->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60
        ]);
    }

    public function me()
    {   //2种方法都可以
        return response()->json(JWTAuth::parseToken()->authenticate());
//        return response()->json(JWTAuth::parseToken()->touser());
    }


}