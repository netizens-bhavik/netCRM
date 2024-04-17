<?php

namespace App\Http\Controllers;

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
    function userDelete($userId){
        try {
            $response = UserServices::userDelete($userId);
            return $response;
        } catch (\Throwable $th) {
            return ApiResponses::errorResponse([], $th->getMessage(), 500);
        }
    }
    function allUsers(){
        try {
            $response = UserServices::allUsers();
            return $response;
        } catch (\Throwable $th) {
            return ApiResponses::errorResponse([], $th->getMessage(), 500);
        }
    }
}
