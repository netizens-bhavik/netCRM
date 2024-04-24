<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use App\Services\RoleServices;
use App\Http\Requests\CreateRoleRequest;
use App\Http\Requests\UpdateRoleRequest;

class RoleController extends Controller
{
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
        try {
            $response = RoleServices::destroy($roleId);
            return $response;
        } catch (\Throwable $th) {
            return ApiResponses::errorResponse([], $th->getMessage(), 500);
        }
    }
}
