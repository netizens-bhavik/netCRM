<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use App\Services\RoleServices;
use App\Http\Requests\CreateRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use Illuminate\Support\Facades\Log;

class RoleController extends Controller
{
    function allRole(){
        try {
            $response = RoleServices::allRole();
            return $response;
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'error' => $th->getMessage()]);
        }
    }
    function getAllRole(){
        try {
            $response = RoleServices::index();
            return $response;
        } catch (\Throwable $th) {
            return ApiResponses::errorResponse([], $th->getMessage(), 500);
        }
    }
    function create(CreateRoleRequest $request){
        try {
            $response = RoleServices::create($request);
            return $response;
        } catch (\Throwable $th) {
            return ApiResponses::errorResponse([], $th->getMessage(), 500);
        }
    }
    function edit($roleId){
        try {
            $response = RoleServices::edit($roleId);
            return $response;
        } catch (\Throwable $th) {
            return ApiResponses::errorResponse([], $th->getMessage(), 500);
        }
    }
    function update(UpdateRoleRequest $request,$roleId){
        try {
            $response = RoleServices::update($request,$roleId);
            return $response;
        } catch (\Throwable $th) {
            return ApiResponses::errorResponse([], $th->getMessage(), 500);
        }
    }
    function destroy($roleId){
        // try {
            return RoleServices::destroy($roleId);
        // } catch (\Throwable $th) {
        //     Log::info($th);
        //     return ApiResponses::errorResponse([], $th->getMessage(), 500);
        // }
    }
}
