<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateNotificationRequest;
use App\Services\NotificationServices;
use Illuminate\Http\Request;
use App\Traits\ApiResponses;

class NotificationController extends Controller
{
    function index($userId)
    {
        try {
            $response = NotificationServices::index($userId);
            return $response;
        } catch (\Throwable $th) {
            return ApiResponses::errorResponse([], $th->getMessage(), 500);
        }
    }
    function create(CreateNotificationRequest $request){
        try {
            $response = NotificationServices::create($request);
            return $response;
        } catch (\Throwable $th) {
            return ApiResponses::errorResponse([], $th->getMessage(), 500);
        }
    }
}
