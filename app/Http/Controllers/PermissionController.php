<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use App\Services\PermissionServices;

class PermissionController extends Controller
{
    function index()
    {
        try {
            $response = PermissionServices::index();
            return $response;
        } catch (\Throwable $th) {
            return ApiResponses::errorResponse([], $th->getMessage(), 500);
        }
    }
}
