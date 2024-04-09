<?php

namespace App\Http\Controllers;

use App\Services\UserServices;
use Illuminate\Http\Request;

class UserController extends Controller
{
    function show(Request $request){
        try {
            $response = UserServices::show($request);
            return $response;
        } catch (\Throwable $th) {
            return $th;
        }
    }
}
