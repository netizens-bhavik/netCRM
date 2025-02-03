<?php

namespace App\Http\Controllers;

use App\Http\Requests\passwordResetRequest;
use App\Http\Requests\RegisteruserRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Services\UserServices;
use Illuminate\Http\Request;
use App\Traits\ApiResponses;

class UserController extends Controller
{
    function show(Request $request)
    {
        try {
            $response = UserServices::show($request);
            return $response;
        } catch (\Throwable $th) {
            return $th;
        }
    }
    function edit($userId)
    {
        try {
            $response = UserServices::edit($userId);
            return $response;
        } catch (\Throwable $th) {
            return ApiResponses::errorResponse([], $th->getMessage(), 500);
        }
    }
    function update(UserUpdateRequest $request, $UserId)
    {
        try {
            $response = UserServices::update($UserId, $request);
            return $response;
        } catch (\Throwable $th) {
            return ApiResponses::errorResponse([], $th->getMessage(), 500);
        }
    }
    function userList(Request $request)
    {
        try {
            $response = UserServices::userList($request);
            return $response;
        } catch (\Throwable $th) {
            return ApiResponses::errorResponse([], $th->getMessage(), 500);
        }
    }
    function userDelete($userId)
    {
        try {
            $response = UserServices::userDelete($userId);
            return $response;
        } catch (\Throwable $th) {
            return ApiResponses::errorResponse([], $th->getMessage(), 500);
        }
    }
    function allUsers(Request $request)
    {
        try {
            $response = UserServices::allUsers($request);
            return $response;
        } catch (\Throwable $th) {
            return ApiResponses::errorResponse([], $th->getMessage(), 500);
        }
    }
    function findUser($userId)
    {
        try {
            $response = UserServices::findUser($userId);
            return $response;
        } catch (\Throwable $th) {
            return ApiResponses::errorResponse([], $th->getMessage(), 500);
        }
    }
    function resetPassword(passwordResetRequest $request, $userId)
    {
        try {
            $response = UserServices::resetPassword($request, $userId);
            return $response;
        } catch (\Throwable $th) {
            return ApiResponses::errorResponse([], $th->getMessage(), 500);
        }
    }
    function forgotPassword(Request $request)
    {
        try {
            $response = UserServices::forgotPassword($request);
            return $response;
        } catch (\Throwable $th) {
            return ApiResponses::errorResponse([], $th->getMessage(), 500);
        }
    }

    public function userCreate(Request $request)
    {
        $response = [
            'status' =>true,
        ];
        return response()->json($response);
    }
}
