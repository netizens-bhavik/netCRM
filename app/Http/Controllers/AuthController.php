<?php

namespace App\Http\Controllers;

use App\Services\AuthServices;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginuserRequest;
use App\Http\Requests\RegisteruserRequest;

class AuthController extends Controller
{
    public function createUser(RegisteruserRequest $request)
    {
        try {
            $response = AuthServices::registerUser($request);
            return $response;
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function loginUser(LoginuserRequest $request)
    {
        try {
            $response = AuthServices::login($request);
            return $response;
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function logout(){
        auth()->user()->tokens()->delete();

        return response()->json([
          "message"=>"logged out"
        ]);
    }
}
