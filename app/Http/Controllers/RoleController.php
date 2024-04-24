<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use App\services\RoleServices;
use App\Http\Requests\RoleRequest;

class RoleController extends Controller
{
    function create(RoleRequest $request){
        try {
            $response = RoleServices::create($request);
            return $response;
        } catch (\Throwable $th) {
            return ApiResponses::errorResponse([], $th->getMessage(), 500);
        }
    }
}
